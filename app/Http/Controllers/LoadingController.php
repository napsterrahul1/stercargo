<?php

namespace App\Http\Controllers;

use App\Exports\PRSExportExcel;
use App\Models\Loading;
use App\Models\PRS;
use App\Models\PRSPackage;
use Auth;
use App\Area;
use App\Branch;
use App\Client;
use App\Cost;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ShipmentActionHelper;
use App\Http\Helpers\StatusManagerHelper;
use App\Http\Helpers\TransactionHelper;
use App\Mission;
use App\Models\Country;
use App\Models\Manifest;
use App\Package;
use App\PackageShipment;
use App\Shipment;
use App\ShipmentMission;
use App\ShipmentSetting;
use App\Http\Helpers\MissionPRNG;
use App\Http\Helpers\ShipmentPRNG;
use Excel;
use App\BusinessSetting;
use App\State;
use App\Transaction;
use App\ShipmentReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use function Psy\sh;
use App\Events\CreateMission;
use App\Events\AddShipment;
use App\Events\UpdateShipment;
use App\Events\UpdateMission;
use App\Events\ShipmentAction;
use App\AddressClient;
use App\Http\Helpers\UserRegistrationHelper;
use Carbon\Carbon;
use App\Exports\ShipmentsExportExcel;
use App\Models\Docket;

class LoadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $shipments = new Loading();
        $type = null;
        $sort_by = null;

        $auth_user = Auth::user();
        if(isset($auth_user))
        {
            if(Auth::user()->user_type == 'customer'){
                $shipments = $shipments->where('client_id', Auth::user()->userClient->client_id);
            }elseif(Auth::user()->user_type == 'branch'){
//                $shipments = $shipments->where('branch_id', Auth::user()->userBranch->branch_id);
            }
        }

        $shipments = $shipments->where('status_id',0)->orderBy('client_id')->orderBy('id','DESC')->paginate(20);
        $actions = new ShipmentActionHelper();
        $actions = $actions->get('all');
        $page_name = translate('All Loading Sheet');
        $status = 'all';
        if($request->is('api/*')){
            return  response()->json($shipments);
        }

        return view('backend.loading.index', compact('shipments', 'page_name', 'type', 'actions', 'status' , 'sort_by'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branchs = Branch::where('is_archived', 0)->get();
        $clients = Client::where('is_archived', 0)->get();
        $dockets = Docket::get();

        return view('backend.loading.create', compact('branchs', 'clients','dockets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());

        try {
            DB::beginTransaction();
            $amount = Docket::docketWeight( implode(',',$request->Shipment['docket']));

            $model = new Loading();
            $model->fill($_POST['Shipment']);
            $model->docket = implode(',',$request->Shipment['docket']);
            $model->total_weight = $amount;
            $model['code'] = Loading::code('LOD');
            if (!$model->save()) {
                return response()->json(['message' => new \Exception()] );
            }
            // $model = $this->storeShipment($request);

            DB::commit();

            // $counter = 0;
            // if (isset($_POST['Package'])) {
            //     if (!empty($_POST['Package'])) {
            //         foreach ($_POST['Package'] as $package) {
            //             $package_shipment = new PRSPackage();
            //             $package_shipment->fill($package);
            //             $package_shipment->foreign_id = $model->id;
            //             $package_shipment->type = 2;
            //             if (!$package_shipment->save()) {
            //                 throw new \Exception();
            //             }
            //         }
            //     }
            // }

            flash(translate("Loading added successfully"))->success();
            return redirect()->route('admin.loading.show', $model->id);
        } catch (\Exception $e) {
            DB::rollback();
            print_r($e->getMessage());
            exit;

            flash(translate("Error"))->error();
            return back();
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shipment = Loading::find($id);
        return view('backend.loading.show', compact('shipment'));
    }

    public function prints($shipment, $type = 'invoice')
    {
        $shipment = Loading::find($shipment);
        if($type == 'label'){
            return view('backend.loading.print', compact('shipment'));
        }else{
            return view('backend.loading.print', compact('shipment'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $shipment = Loading::find($id);
        $branchs = Branch::where('is_archived', 0)->get();
        $clients = Client::where('is_archived', 0)->get();
         $dockets = Docket::get();
        return view('backend.loading.edit', compact('branchs', 'clients', 'shipment','dockets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $shipment)
    {

        try {
            DB::beginTransaction();
            $amount = Docket::docketWeight( implode(',',$request->Shipment['docket']));

            $model = Loading::find($shipment);
            $model->fill($_POST['Shipment']);
            $model->docket = implode(',',$request->Shipment['docket']);
             $model->total_weight = $amount;

            if (!$model->save()) {
                throw new \Exception();
            }
            foreach (\App\PackageShipment::where('shipment_id', $model->id)->get() as $pack) {
                $pack->delete();
            }
            $all = PRSPackage::where('type',2)->where('foreign_id',$model->id)->delete();
            $counter = 0;
            if (isset($_POST['Package'])) {
                if (!empty($_POST['Package'])) {

                    foreach ($_POST['Package'] as $package) {
                        $package_shipment = new PRSPackage();
                        $package_shipment->fill($package);
                        $package_shipment->foreign_id = $model->id;
                        $package_shipment->type = 2;
                        if (!$package_shipment->save()) {
                            throw new \Exception();
                        }
                        $counter++;
                    }
                }
            }

//            event(new UpdateShipment($model));
            DB::commit();
            flash(translate("Loading Updated successfully"))->success();
            $route = 'admin.loading.index';
            return execute_redirect($request, $route);
        } catch (\Exception $e) {
            DB::rollback();
            print_r($e->getMessage());
            exit;

            flash(translate("Error"))->error();
            return back();
        }
    }

    public function converttomanifest(Request $request, $shipment)
    {
         $model = Loading::find($shipment);
        $mn = new Manifest();
            $mn->docket = $model->docket;
            $mn->code = rand(1,9999);
            $mn->total_docket = $model->total_docket;
            $mn->date = $model->date;
            $mn->origin = $model->origin;
            $mn->destination = $model->destination;
            $mn->receiver_name = $model->receiver_name;
            $mn->sender_name = $model->sender_name;
            // $mn->boy_name = $model->boy_name;
            $mn->total_weight = $model->total_weight;
            $mn->total_package = $model->total_package;
            $mn->status_id = 1;
            $mn->save();
            flash(translate("Loading Converted successfully"))->success();
            $route = 'admin.loading.index';
            return execute_redirect($request, $route);



    }

    public function import(Request $request)
    {
        $shipment = new Shipment;
        $columns = $shipment->getTableColumns();
        return view('backend.prs.import',['columns'=>$columns]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function shipmentCalc()
    {
        return view('backend.prs.shipment_calc');
    }

    public function exportShipments($status)
    {
        $excelName = '';
        $time_zone = BusinessSetting::where('type', 'timezone')->first();
        if($time_zone->value == null){
            $excelName = 'export_prs_'.Carbon::now()->toDateString().'.xlsx';
        }else {
            $excelName = 'export_prs_'.Carbon::now($time_zone->value)->toDateString().'.xlsx';
        }

        return Excel::download( new PRSExportExcel($status) , $excelName );
    }




}

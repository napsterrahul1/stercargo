<?php

namespace App\Http\Controllers;

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

class LoadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $shipments = new Shipment();
        $type = null;
        $sort_by = null;

        $auth_user = Auth::user();
        if(isset($auth_user))
        {
            if(Auth::user()->user_type == 'customer'){
                $shipments = $shipments->where('client_id', Auth::user()->userClient->client_id);
            }elseif(Auth::user()->user_type == 'branch'){
                $shipments = $shipments->where('branch_id', Auth::user()->userBranch->branch_id);
            }
        }

        $shipments = $shipments->with(['pay','from_address'])->orderBy('client_id')->orderBy('id','DESC')->paginate(20);
        $actions = new ShipmentActionHelper();
        $actions = $actions->get('all');
        $page_name = translate('All PRS');
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
        return view('backend.loading.create', compact('branchs', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
                $model = $this->storeShipment($request);
            DB::commit();
            flash(translate("Shipment added successfully"))->success();
            return redirect()->route('admin.shipments.show', $model->id);
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
        $shipment = Shipment::find($id);
        return view('backend.loading.show', compact('shipment'));
    }

    public function print($shipment, $type = 'invoice')
    {
        $shipment = Shipment::find($shipment);
        if($type == 'label'){
            return view('backend.loading.print_label', compact('shipment'));
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
        $branchs = Branch::where('is_archived', 0)->get();
        $clients = Client::where('is_archived', 0)->get();
        $shipment = Shipment::find($id);
        return view('backend.loading.edit', compact('branchs', 'clients', 'shipment'));
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
            $model = Shipment::find($shipment);


            $model->fill($_POST['Shipment']);


            if (!$model->save()) {
                throw new \Exception();
            }
            foreach (\App\PackageShipment::where('shipment_id', $model->id)->get() as $pack) {
                $pack->delete();
            }
            $counter = 0;
            if (isset($_POST['Package'])) {

                if (!empty($_POST['Package'])) {

                    if (isset($_POST['Package'][$counter]['package_id'])) {

                        foreach ($_POST['Package'] as $package) {
                            $package_shipment = new PackageShipment();
                            $package_shipment->fill($package);
                            $package_shipment->shipment_id = $model->id;
                            if (!$package_shipment->save()) {
                                throw new \Exception();
                            }
                        }
                    }
                }
            }

            event(new UpdateShipment($model));
            DB::commit();
            flash(translate("Shipment added successfully"))->success();
            $route = 'admin.shipments.index';
            return execute_redirect($request, $route);
        } catch (\Exception $e) {
            DB::rollback();
            print_r($e->getMessage());
            exit;

            flash(translate("Error"))->error();
            return back();
        }
    }


    public function import(Request $request)
    {
        $shipment = new Shipment;
        $columns = $shipment->getTableColumns();
        return view('backend.loading.import',['columns'=>$columns]);
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


    public function export($status)
    {
        $excelName = '';
        $time_zone = BusinessSetting::where('type', 'timezone')->first();
        if($time_zone->value == null){
            $excelName = 'export_shipments_'.Carbon::now()->toDateString().'.xlsx';
        }else {
            $excelName = 'export_shipments_'.Carbon::now($time_zone->value)->toDateString().'.xlsx';
        }

        return Excel::download( new ShipmentsExportExcel($status) , $excelName );
    }


}

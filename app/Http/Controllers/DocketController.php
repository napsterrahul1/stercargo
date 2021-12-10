<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Client;
use App\Http\Helpers\UserRegistrationHelper;
use App\User;
use App\UserClient;
use DB;
use Auth;
use App\BusinessSetting;
use App\Events\AddClient;
use App\AddressClient;
use App\ClientPackage;
use Carbon\Carbon;
use App\Package;
use App\Models\Docket;
use App\Models\PRS;
use App\Branch;
use App\Area;

class DocketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Docket::paginate(15);
        return view('backend.docket.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branchs = Docket::get(); 
        $areas = Area::select('name','id')->get();

        return view('backend.docket.create',compact('branchs','areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{   
          $validator = Validator::make($request->all(), [
                'invoice_no' => 'required',
                'date' => 'required',
                'from_source' => ['email','required','unique:users'],
                'to_destination' => 'required',
            ]);
 
            DB::beginTransaction();
            $model = new Docket();
            $input = $request->all();
             $user = Docket::create($input);
             DB::commit();
            flash(translate("Client added successfully"))->success();
            $route = 'dockets.index';
            return execute_redirect($request,$route);
        }catch(\Exception $e){
            DB::rollback();
            print_r($e->getMessage());
            exit;
            
            flash(translate("Error"))->error();
            return back();
        }
    }

    /**
     * 
     * Show the form for register public client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('backend.clients.register');
    }

    /**
     * Save register data in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'password' => 'required|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'required',
                'email' => ['email','required','unique:users'],
                'mobile' => 'required',
                'condotion_agreement' => 'required',
            ]);

            if ($validator->fails()) 
            {
                return back()
                ->withErrors($validator)
                ->withInput();
            }
        
            DB::beginTransaction();
            $model = new Client();
            
            
            $model->name = $request->name;
            $model->email = $request->email;
            $model->responsible_mobile = $request->responsible_mobile;
            $model->is_archived = 1;
            $model->code = -1;
          
            if (!$model->save()){
                throw new \Exception();
            }

            $model->created_by_type = 'client';
            $model->created_by      = -1;
            $model->pickup_cost     = \App\ShipmentSetting::getVal('def_pickup_cost');
            $model->supply_cost     = \App\ShipmentSetting::getVal('def_supply_cost');
            $model->code            = $model->id;
            if (!$model->save()){
                throw new \Exception();
            }
            $userRegistrationHelper = new UserRegistrationHelper();
            $userRegistrationHelper->setEmail($model->email); 
            $userRegistrationHelper->setName($model->name);
            $userRegistrationHelper->setApiToken();
            $userRegistrationHelper->setPassword($request->password);
            $userRegistrationHelper->setRoleID(UserRegistrationHelper::MAINCLIENT);
            $response = $userRegistrationHelper->save();
            if(!$response['success']){
                throw new \Exception($response['error_msg']);
            }
            $userClient = new UserClient();
            $userClient->user_id = $response['user_id'];
            $userClient->client_id = $model->id;
            if (!$userClient->save()){
                throw new \Exception();
            }

            event(new AddClient($model));
            DB::commit();
            
            flash(translate("Your account has been created successfully"))->success();

            Auth::loginUsingId($response['user_id']);

            $route = 'admin.dashboard';
            return execute_redirect($request,$route);
        }catch(\Exception $e){
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
        $client = Docket::where('id', $id)->first();
       // $branchs = Branch::where('is_archived',0)->get();
        $areas= Area::get();
        $areas = Area::select('name','id')->get();
        if($client != null){
            return view('backend.docket.show',compact(['client','areas']));
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Docket::where('id', $id)->first();
       // $branchs = Branch::where('is_archived',0)->get();
        $areas= Area::get();
        $areas = Area::select('name','id')->get();
        if($client != null){
            return view('backend.docket.edit',compact(['client','areas']));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $client)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('This action is disabled in demo mode'))->error();
            return back();
        }
        try{    
            DB::beginTransaction();
            $model = Docket::find($client);
               $input = $request->all();
             $model->update($input);


            DB::commit();
            flash(translate("Docket updated successfully"))->success();
            $route = 'dockets.index';
            return execute_redirect($request,$route);
        }catch(\Exception $e){
            DB::rollback();
            print_r($e->getMessage());
            exit;
            
            flash(translate("Error"))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($client)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('This action is disabled in demo mode'))->error();
            return back();
        }

        $prs = DB::table("prs")
    ->select('id','docket')
       ->whereRaw("find_in_set($client,docket)")
    ->first();
    if($prs)
    {
        flash(translate('Already is in use'))->error();
            return back();

    }
        $model = Docket::findOrFail($client)->delete();
        
            flash(translate('Docket has been deleted successfully'))->success();
            return redirect()->back();
       
    }

    public function addNewAddress(Request $request)
    {
        $client_address = new AddressClient();
        $client_address->client_id                 = $request->client_id;
        $client_address->address                   = $request->address;
        $client_address->country_id                = $request->country;
        $client_address->state_id                  = $request->state;
        if(isset($request->area)){
            $client_address->area_id               = $request->area;
        }

        $googel_map = BusinessSetting::where('type', 'google_map')->first();
        if($googel_map->value == 1){
            $client_address->client_street_address_map = $request->client_street_address_map;
            $client_address->client_lat                = $request->client_lat;
            $client_address->client_lng                = $request->client_lng;
            $client_address->client_url                = $request->client_url;
        }
        
        if (!$client_address->save()) {
            throw new \Exception();
        }

        $client_id  = $request->client_id;
        $addressess = AddressClient::where('client_id', $client_id)->get();
        return response()->json($addressess);
    }

    public function getOneAddress(Request $request)
    {
        $address_id = $_GET['address_id'];
        $address = AddressClient::where('id', $address_id)->get();
        return response()->json($address);
    }
    
    public function addNewAddressAPI(Request $request)
    {
        if($request->is('api/*')){
            $token = $request->header('token');
            if(isset($token))
            {
                $user = User::where('api_token',$token)->first();

                if(!$user)
                {
                    return response()->json('Not Authorized');
                }
                $addresses = $this->addNewAddress($request);
                return response()->json($addresses);    
            }else{
                return response()->json('Not Authorizedd');
            }      
        }
    }

    public function getAddressesAPI(Request $request)
    {
        if($request->is('api/*')){
            $token = $request->header('token');
            if(isset($token))
            {
                $user = User::where('api_token',$token)->first();

                if(!$user)
                {
                    return response()->json('Not Authorized');
                }

                $addresses = AddressClient::where('client_id', $_GET['client_id'])->get();
                return response()->json($addresses);    
            }else{
                return response()->json('Not Authorizedd');
            }      
        }
        
    }
}

@extends('backend.layouts.app')
@php
    $user_type = Auth::user()->user_type;
    $staff_permission = json_decode(Auth::user()->staff->role->permissions ?? "[]");
    $auth_user = Auth::user();
@endphp

@section('sub_title'){{translate('PRS')}}@endsection
@section('subheader')
    <!--begin::Subheader-->
    <div class="py-2 subheader py-lg-6 subheader-solid" id="kt_subheader">
        <div class="flex-wrap container-fluid d-flex align-items-center justify-content-between flex-sm-nowrap">
            <!--begin::Info-->
            <div class="flex-wrap mr-1 d-flex align-items-center">
                <!--begin::Page Heading-->
                <div class="flex-wrap mr-5 d-flex align-items-baseline">
                    <!--begin::Page Title-->
                    <h5 class="my-1 mr-5 text-dark font-weight-bold">{{translate('PRS')}}</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="p-0 my-2 mr-5 breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard')}}" class="text-muted">{{translate('Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted">PRS</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                    <a href="{{ route('admin.prs.create') }}" class="btn btn-light-primary font-weight-bolder btn-sm"><i class="flaticon2-add-1"></i> {{translate('Add New PRS')}}</a>
                    @if(in_array($auth_user->user_type ,['customer']))
                        <a href="{{ route('admin.prs.import') }}" class="ml-3 btn btn-light-primary font-weight-bolder btn-sm">{{translate('Import Shipments')}}</a>
                    @endif
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->
@endsection

@section('content')
<!--begin::Card-->
<div class="card card-custom gutter-b">
    <div class="flex-wrap py-3 card-header">
        <div class="card-title">
            <h3 class="card-label">
                {{$page_name}}
            </h3>
        </div>
        @if(count($actions) > 0)
        <div class="card-toolbar" id="actions-button">
            <!--begin::Dropdown-->
            <div class="mr-2 dropdown dropdown-inline">
                <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/PenAndRuller.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>{{translate('Actions')}}</button>
                <!--begin::Dropdown Menu-->
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                    <!--begin::Navigation-->
                    <ul class="py-2 navi flex-column navi-hover">
                        <li class="pb-2 navi-header font-weight-bolder text-uppercase font-size-sm text-primary">{{translate('Choose an option:')}}</li>
                        <li class="navi-item">
                            @php
                                $action_counter = 0;
                            @endphp
                            @foreach($actions as $action)
                                @if(in_array($auth_user->user_type ,$action['user_role']) || in_array($action['permissions'] ?? "", json_decode($auth_user->staff->role->permissions ?? "[]")) )
                                    @if($action['index'] == true)
                                        @php
                                            $action_counter++;
                                        @endphp
                                        <a href="#" class="action_checker navi-link @if(!isset($action['js_function_caller'])) action-caller @endif" @if(isset($action['js_function_caller'])) onclick="{{$action['js_function_caller']}}" @endif data-url="{{$action['url']}}" data-method="{{$action['method']}}">
                                            <span class="navi-icon">
                                                <i class="{{$action['icon']}}"></i>
                                            </span>
                                            <span class="navi-text">{{$action['title']}}</span>
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                        </li>

                    </ul>
                    <!--end::Navigation-->
                </div>
                <!--end::Dropdown Menu-->
            </div>
            @if($action_counter == 0)
                <script>
                    document.getElementById("actions-button").style.display = "none";
                </script>
            @endif
            <!--end::Dropdown-->
        </div>
        @endif
    </div>

    <div class="card-body">
        <!--begin::Search Form-->
        <form method="GET" action="{{url()->current()}}" id="search_form">
            <div class="mb-7">
                <div class="row align-items-center">


                    <div class="mt-5 col-lg-3 col-xl-12 mt-lg-0 d-flex">


                        <a href="{{route('admin.prs.export', $status)}}" class="ml-auto px-6 btn btn-light-primary font-weight-bold">{{translate('Export PRS')}}</a>
                    </div>

                </div>
        </form>
    </div>
    <!--end::Search Form-->
    <form id="tableForm">
        @csrf()
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th>{{translate('Code')}}</th>
                    <th>{{translate('PRS Date')}}</th>
                    @if($status == "all") <th>{{translate('Status')}}</th> @endif
                    <th>{{translate('Vendor Name')}}</th>


                    <th>{{translate('Total Pkg')}}</th>
                    <th>{{translate('Total Weight')}}</th>
                    <th>{{translate('Total Amount')}}</th>
                    <th>{{translate('Paid')}}</th>

                    @if($status == \App\Shipment::CAPTAIN_ASSIGNED_STATUS || $status == \App\Shipment::RECIVED_STATUS)
                    <th>{{translate('Driver')}}</th>
                    @endif
                    @if($status == \App\Shipment::APPROVED_STATUS || $status == \App\Shipment::CAPTAIN_ASSIGNED_STATUS || $status == \App\Shipment::RECIVED_STATUS)
                    <th>{{translate('Mission')}}</th>
                    @endif
                    <th class="text-center">{{translate('Created At')}}</th>
                    @if($status != "all") <th class="text-center">{{translate('Options')}}</th> @endif
                    <th  width="10%" class="text-center">{{translate('Options')}}</th>

                </tr>
            </thead>
            <tbody>
            


                @foreach($shipments as $key=>$shipment)

                    <tr>
                        <td width="3%"><a href="{{url('admin/prs/'.$shipment->id )}}">{{ ($key+1) + ($shipments->currentPage() - 1)*$shipments->perPage() }}</a></td>

                        <td width="5%"><a href="{{url('admin/prs/'. $shipment->id )}}">{{$shipment->code}}</a></td>

                        <td>{{$shipment->date}}</td>
                        @if($status == "all") <td>{{$shipment->getStatus()}}</td> @endif
                        <td>
                            {{$shipment->vendor_name }}
                        </td>
                        <td>

                         <?php $pcs =  \App\Models\Docket::docketPackage($shipment->docket); ?>
                            {{$pcs ? $pcs : 0 }}
 
                        </td>
                        <td>
                            <?php $weight =  \App\Models\Docket::docketWeight($shipment->docket); ?>
                            {{$weight ? $weight : 0 }}
                        </td> 
                        <td>
                            <?php $amount =  \App\Models\Docket::docketAmount($shipment->docket); ?>
                            {{$amount ? $amount : 0 }}
                        </td>
                            <td>@if($shipment->paid == 1) {{translate('Paid')}} @else - @endif</td>

                            @if($status == \App\Shipment::CAPTAIN_ASSIGNED_STATUS || $status == \App\Shipment::RECIVED_STATUS)
                                <td><a href="{{route('admin.captains.show', $shipment->captain_id)}}">@isset($shipment->captain_id) {{$shipment->captain->name}} @endisset</a></td>
                            @endif
                        @if($status == \App\Shipment::APPROVED_STATUS || $status == \App\Shipment::CAPTAIN_ASSIGNED_STATUS || $status == \App\Shipment::RECIVED_STATUS )
                            <td>@isset($shipment->current_mission->id) <a href="{{route('admin.missions.show', $shipment->current_mission->id)}}"> {{$shipment->current_mission->code}}</a> @endisset</td>
                        @endif
                        <td class="text-center">
                            {{$shipment->created_at->format('Y-m-d')}}
                        </td>  <td class="text-center">
                               
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('admin.prs.show', $shipment->id)}}" title="{{ translate('Show') }}">
                                    <i class="las la-eye"></i>
                                </a>

                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('admin.prs.edit', $shipment->id)}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>

                            </td>
                    </tr>


                @endforeach

            </tbody>
        </table>

        <!-- Assign-to-captain Modal -->
        <div id="assign-to-captain-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if(isset($status))
                    @if($status == \App\Shipment::SAVED_STATUS || $status == \App\Shipment::REQUESTED_STATUS)
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{translate('Create Pickup Mission')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="Mission[to_branch_id]" class="form-control branch_hidden" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Sender')}}:</label>
                                    <input type="hidden" name="Mission[sender_name]" value="" id="pick_up_client_id_hidden">
                                  
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Pickup Address')}}:</label>
                                    <input type="text" name="Mission[address]" class="form-control" id="pick_up_address" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Type')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Pickup')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Status')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Requested')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    @elseif($status == \App\Shipment::APPROVED_STATUS)
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{translate('Create Delivery Mission')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- <input type="hidden" name="Mission[to_branch_id]" class="form-control branch_hidden" /> --}}
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Customer/Sender')}}:</label>
                                    <input type="hidden" name="Mission[sender_name]" value="" id="pick_up_client_id_hidden">
                                    <select style="background:#f3f6f9;color:#3f4254;" name="Mission[sender_name]" class="form-control" id="pick_up_client_id" disabled>
                                        @foreach(\App\Client::all() as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Reciver')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" name="Mission[name]" class="form-control" id="delivery_name" disabled />
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('State')}}:</label>
                                    <input type="hidden" name="Mission[state]" class="form-control" id="delivery_state_hidden" />
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control" id="delivery_state" disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Area')}}:</label>
                                    <input type="hidden" name="Mission[area]" class="form-control" id="delivery_state_hidden" />
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control" id="delivery_area_hidden" disabled />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Delivery Address')}}:</label>
                                    <input type="text" name="Mission[address]" class="form-control" id="delivery_address" />

                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Type')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Delivery')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Status')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Requested')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    @elseif($status == \App\Shipment::DELIVERED_STATUS)
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{translate('Create Supply Mission')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="Mission[to_branch_id]" class="form-control branch_hidden" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Sender')}}:</label>
                                    <input type="hidden" name="Mission[sender_name]" value="" id="pick_up_client_id_hidden">
                                    <select name="Mission[sender_name]" class="form-control" id="pick_up_client_id" disabled>
                                        @foreach(\App\Client::all() as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Supply Address')}}:</label>
                                    <input type="text" name="Mission[address]" class="form-control" id="supply_address" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Type')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Supply')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Status')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Requested')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    @elseif($status == \App\Shipment::RETURNED_STOCK)
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{translate('Create Return Mission')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Customer/Sender')}}:</label>
                                    <input type="hidden" name="Mission[sender_name]" value="" id="pick_up_client_id_hidden">
                                    <select name="Mission[sender_name]" class="form-control" id="pick_up_client_id" disabled>
                                        @foreach(\App\Client::all() as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Address')}}:</label>
                                    <input type="text" id="return_address" name="Mission[address]" class="form-control" />

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('Amount')}}:</label>
                                    <input type="number" name="Mission[amount]" class="form-control" value="0" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Type')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Return')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Status')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Requested')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Create Mission')}}</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
        @endif

        <div id="transfer-to-branch-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title h6">{{translate('Create Transfer Mission')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('From Branch')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" id="from_branch_transfer" type="text" class="form-control disabled" value="{{translate('Transfer')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{translate('To Branch')}}:</label>

                                    <select name="Mission[to_branch_id]" id="to_branch_id" class="form-control">
                                        <option value="" disabled selected hidden>Choose Branch...</option>
                                        @foreach(\App\Branch::all() as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Type')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Transfer')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Status')}}:</label>
                                    <input style="background:#f3f6f9;color:#3f4254;" type="text" class="form-control disabled" value="{{translate('Requested')}}" disabled="disabled" readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('Close')}}</button>
                        <input type="submit" class="btn btn-primary"  value="{{translate('Create Mission')}}" id="submit_transfer"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="aiz-pagination">
        {{ $shipments->appends(request()->input())->links() }}
    </div>
</div>
</div>
{!! hookView('shipment_addon',$currentView) !!}

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    $(document).on('click','#submit_transfer',function(){
        $('#tableForm').submit();
    });
    $('#reset_search').click(function(e) {
        e.preventDefault();
        $('#search_form')[0].reset();
    });
    $('.select-client').select2();
    $('.select-type').select2();
    $('.select-branch').select2();
    $('.select-from-country').select2();
    $('.select-from-region').select2({
        placeholder: "Select Country First",
    });
    $('.select-from-area').select2({
        placeholder: "Select Region First",
    });
    $('.select-to-country').select2();
    $('.select-to-region').select2({
        placeholder: "Select Country First",
    });
    $('.select-to-area').select2({
        placeholder: "Select Region First",
    });
    $('.select-sort').select2();

    $('#change-country').change(function() {
        var id = $(this).val();
        $.get("{{route('admin.shipments.get-states-ajax')}}?country_id=" + id, function(data) {
            $('select[name ="from_region_id"]').append('<option>Select Region</option>');
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                $('select[name ="from_region_id"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
        });
    });


    $('#change-country-to').change(function() {
        var id = $(this).val();
        $.get("{{route('admin.shipments.get-states-ajax')}}?country_id=" + id, function(data) {
            $('select[name ="to_region_id"]').append('<option>Select Region</option>');
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                $('select[name ="to_region_id"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
        });
    });

    $('#change-state-from').change(function() {
        var id = $(this).val();
        $.get("{{route('admin.shipments.get-areas-ajax')}}?state_id=" + id, function(data) {
            $('select[name ="from_area_id"]').append('<option>Select Area</option>');
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                $('select[name ="from_area_id"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
        });
    });
    $('#change-state-to').change(function() {
        var id = $(this).val();
        $.get("{{route('admin.shipments.get-areas-ajax')}}?state_id=" + id, function(data) {
            $('select[name ="to_area_id"]').append('<option>Select Area</option>');
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                $('select[name ="to_area_id"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
        });
    });

    function openCaptainModel(element, e) {
        var selected = [];
        var selected_payment_method = [];
        var count_payment_method = 0 ;

        var selected_address_sender = [];
        var selected_address = [];
        var selected_branch_hidden = [];
        var mission_id = [];
        $('.sh-check:checked').each(function() {
            selected.push($(this).data('clientid'));
            selected_payment_method.push($(this).data('paymentmethodid'));
            selected_address_sender.push($(this).data('clientaddresssender'));
            selected_address.push($(this).data('clientaddress'));
            selected_branch_hidden.push($(this).data('branchid'));
            mission_id.push($(this).data('missionid'));
        });
        if (selected.length != 0)
        {
            if(mission_id[0] == ""){

                var sum = selected.reduce(function(acc, val) { return acc + val; },0);
                var check_sum = selected[0] * selected.length;

                if (selected.length == 1 || sum == check_sum) {

                    selected_payment_method.forEach((element, index) => {
                        if(selected_payment_method[0] == selected_payment_method[index]){
                            count_payment_method++;
                        }
                    });
                    if(selected_payment_method.length == count_payment_method)
                    {
                        $('#tableForm').attr('action', $(element).data('url'));
                        $('#tableForm').attr('method', $(element).data('method'));
                        $('#pick_up_address').val(selected_address_sender[0]);
                        $('#assign-to-captain-modal').modal('toggle');
                        $('#supply_address').val(selected_address_sender[0]);
                        $('#return_address').val(selected_address_sender[0]);
                        $('#pick_up_client_id').val(selected[0]);
                        $('#pick_up_client_id_hidden').val(selected[0]);
                        $('.branch_hidden').val(selected_branch_hidden[0]);
                    }else{
                        Swal.fire("{{translate('Select shipments of the same payment method')}}", "", "error");
                    }
                } else if (selected.length == 0) {
                    Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
                }else{
                    Swal.fire("{{translate('Select shipments of the same client to Assign')}}", "", "error");
                }

            }else{
                Swal.fire("{{translate('This Shipment Already In Mission')}}", "", "error");
            }

        }else{
            Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
        }

    }

    function openAssignShipmentCaptainModel(element, e) {
        var selected = [];
        var selected_payment_method = [];
        var count_payment_method = 0 ;
        // var selected_address = [];
        // var selected_name = [];
        // var selected_state = [];
        // var selected_state_hidden = [];
        // var selected_area = [];
        // var selected_area_hidden = [];
        // var selected_branch_hidden = [];
        var mission_id = [];
        $('.sh-check:checked').each(function() {
            selected.push($(this).data('clientid'));
            selected_payment_method.push($(this).data('paymentmethodid'));
            // selected_address.push($(this).data('clientaddress'));
            // selected_name.push($(this).data('clientname'));
            // selected_state.push($(this).data('clientstate'));
            // selected_state_hidden.push($(this).data('clientstatehidden'));
            // selected_area.push($(this).data('clientarea'));
            // selected_area_hidden.push($(this).data('clientareahidden'));
            // selected_branch_hidden.push($(this).data('branchid'));
            mission_id.push($(this).data('missionid'));
        });

        if (selected.length != 0)
        {
            if(mission_id[0] == ""){
                // var sum = selected.reduce(function(acc, val) { return acc + val; },0);
                // var check_sum = selected[0] * selected.length;

                if (selected.length != 0) {
                    selected_payment_method.forEach((element, index) => {
                        if(selected_payment_method[0] == selected_payment_method[index]){
                            count_payment_method++;
                        }
                    });
                    if(selected_payment_method.length == count_payment_method)
                    {
                        $('#tableForm').attr('action', $(element).data('url'));
                        $('#tableForm').attr('method', $(element).data('method'));
                        $('#assign-to-captain-modal').modal('toggle');
                        // $('#delivery_address').val(selected_address[0]);
                        // $('#delivery_name').val(selected_name[0]);
                        // $('#delivery_state').val(selected_state[0]);
                        // $('#delivery_state_hidden').val(selected_state_hidden[0]);
                        // $('#delivery_area').val(selected_area[0]);
                        // $('#delivery_area_hidden').val(selected_area_hidden[0]);
                        // $('.branch_hidden').val(selected_branch_hidden[0]);
                        // $('#pick_up_client_id').val(selected[0]);
                        // $('#pick_up_client_id_hidden').val(selected[0]);
                    }else{
                        Swal.fire("{{translate('Select shipments of the same payment method')}}", "", "error");
                    }

                }else {
                    Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
                }

            }else{
                Swal.fire("{{translate('This Shipment Already In Mission')}}", "", "error");
            }

        }else{
            Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
        }


    }

    function openTransferShipmentCaptainModel(element, e) {
        var selected = [];
        var branchId = '';
        var branchName = '';
        var mission_id = [];
        var selected_payment_method = [];
        var count_payment_method = 0 ;

        $('#to_branch_id option').css("display","block");


        $('.sh-check:checked').each(function() {
            selected_payment_method.push($(this).data('paymentmethodid'));
            selected.push($(this).data('clientid'));
            branchId = $(this).data('branchid');
            branchName = $(this).data('branchname');
            mission_id.push($(this).data('missionid'));
        });

        if (selected.length != 0)
        {
            if(mission_id[0] == ""){
                var sum = selected.reduce(function(acc, val) { return acc + val; },0);
                var check_sum = selected[0] * selected.length;

                if (selected.length == 1 || sum == check_sum ) {

                    selected_payment_method.forEach((element, index) => {
                        if(selected_payment_method[0] == selected_payment_method[index]){
                            count_payment_method++;
                        }
                    });
                    if(selected_payment_method.length == count_payment_method)
                    {
                        $('#assign-to-captain-modal').remove();
                        $('#tableForm').attr('action', $(element).data('url'));
                        $('#tableForm').attr('method', $(element).data('method'));

                        document.getElementById("from_branch_transfer").value = branchName;
                        $('#to_branch_id option[value='+ branchId +']').css("display","none");
                        $('#to_branch_id option[value='+ branchId +']').find('option:selected').remove();
                        $('#transfer-to-branch-modal').modal('toggle');
                    }else{
                        Swal.fire("{{translate('Select shipments of the same payment method')}}", "", "error");
                    }

                } else if (selected.length == 0) {
                    Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
                }else{
                    Swal.fire("{{translate('Select shipments of the same client to Assign')}}", "", "error");
                }

            }else{
                Swal.fire("{{translate('This Shipment Already In Mission')}}", "", "error");
            }

        }else{
            Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
        }
    }

    // function check_client(parent_checkbox,client_id) {
    //     // if(parent_checkbox.checked){
    //     //     console.log("checked");
    //     // }
    //     checkboxs = document.getElementsByClassName("checkbox-client-id-"+client_id);
    //     for (let index = 0; index < checkboxs.length; index++) {
    //         checkboxs[index].checked = parent_checkbox.checked;
    //     }
    // }

    $(document).ready(function() {

        $('.action-caller').on('click', function(e) {
            e.preventDefault();
            var selected = [];
            $('.sh-check:checked').each(function() {
                selected.push($(this).data('clientid'));
            });
            if (selected.length > 0) {
                $('#tableForm').attr('action', $(this).data('url'));
                $('#tableForm').attr('method', $(this).data('method'));
                $('#tableForm').submit();
            } else if (selected.length == 0) {
                Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
            }

        });

        // FormValidation.formValidation(
        //     document.getElementById('tableForm'), {
        //         fields: {
        //             "Mission[address]": {
        //                 validators: {
        //                     notEmpty: {
        //                         message: '{{translate("This is required!")}}'
        //                     }
        //                 }
        //             },
        //             "Mission[client_id]": {
        //                 validators: {
        //                     notEmpty: {
        //                         message: '{{translate("This is required!")}}'
        //                     }
        //                 }
        //             },
        //             "Mission[to_branch_id]": {
        //                 validators: {
        //                     notEmpty: {
        //                         message: '{{translate("This is required!")}}'
        //                     }
        //                 }
        //             }


        //         },


        //         plugins: {
        //             autoFocus: new FormValidation.plugins.AutoFocus(),
        //             trigger: new FormValidation.plugins.Trigger(),
        //             // Bootstrap Framework Integration
        //             bootstrap: new FormValidation.plugins.Bootstrap(),
        //             // Validate fields when clicking the Submit button
        //             submitButton: new FormValidation.plugins.SubmitButton(),
        //             // Submit the form when all fields are valid
        //             defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        //             icon: new FormValidation.plugins.Icon({
        //                 valid: 'fa fa-check',
        //                 invalid: 'fa fa-times',
        //                 validating: 'fa fa-refresh',
        //             }),
        //         }
        //     }
        // );

    });
</script>

@endsection

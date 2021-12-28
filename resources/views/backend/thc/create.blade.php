@extends('backend.layouts.app')

@section('sub_title'){{translate('Create loading')}}@endsection


@section('subheader')
    <!--begin::Subheader-->
    <div class="py-2 subheader py-lg-6 subheader-solid" id="kt_subheader">
        <div class="flex-wrap container-fluid d-flex align-items-center justify-content-between flex-sm-nowrap">
            <!--begin::Info-->
            <div class="flex-wrap mr-1 d-flex align-items-center">
                <!--begin::Page Heading-->
                <div class="flex-wrap mr-5 d-flex align-items-baseline">
                    <!--begin::Page Title-->
                    <h5 class="my-1 mr-5 text-dark font-weight-bold">{{ translate('Create THC') }}</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="p-0 my-2 mr-5 breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard')}}" class="text-muted">{{translate('Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.shipments.index')}}" class="text-muted">{{translate('THC')}}</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted">{{ translate('Create THC') }}</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->
@endsection

@section('content')

@section('sub_title'){{translate('Create New Shipment')}}@endsection
@php
    $auth_user = Auth::user();


@endphp
<style>
    label {
        font-weight: bold !important;
    }

    .select2-container {
        display: block !important;
    }
</style>
<div class="mx-auto col-lg-12">
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('THC Info')}}</h5>
        </div>



        <form class="form-horizontal" action="{{ route('admin.thc.store') }}" id="kt_form_1" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">


                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                @if(\App\ShipmentSetting::getVal('is_date_required') == '1' || \App\ShipmentSetting::getVal('is_date_required') == null)
                                <div class="form-group">
                                    <label>{{translate('THC Date')}}:</label>
                                    <div class="input-group date">
                                        @php
                                            $defult_shipping_date = \App\ShipmentSetting::getVal('def_shipping_date');
                                            if($defult_shipping_date == null )
                                            {
                                                $shipping_data = \Carbon\Carbon::now()->addDays(0);
                                            }else{
                                                $shipping_data = \Carbon\Carbon::now()->addDays($defult_shipping_date);
                                            }

                                        @endphp
                                        <input type="text" placeholder="{{translate('THC Date')}}" value="" name="Shipment[date]" autocomplete="off" class="form-control" id="kt_datepicker_3" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('THC Number')}}:</label>
                                    <input type="text" placeholder="{{translate('THC Number')}}" name="Shipment[thc_number]" class="form-control" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label>{{translate('Docket Number')}}:</label>
                                    <select class="form-control kt-select2 select-branch" name="Shipment[docket][]" id="docket" multiple>
                                        <option></option>
                                        @foreach($dockets as $docket)
                        <option value="{{$docket->id}}">{{$docket->code}}</option>
                                          
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group client-select">
                                    <label>{{translate('Customer')}}:</label>

                                        <select class="form-control kt-select2 select-client" id="client-id" onchange="selectIsTriggered()" name="Shipment[client_id]">
                                            <option></option>
                                            @foreach($clients as $client)
                                            <option value="{{$client->id}}" data-phone="{{$client->responsible_mobile}}">{{$client->name}}</option>
                                            @endforeach

                                        </select>


                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vendor Name')}}:</label>
                                    <input type="text" placeholder="{{translate('Vendor Name')}}" name="Shipment[vendor_name]" class="form-control" />

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vehicle Number')}}:</label>
                                    <input type="text" placeholder="{{translate('Vehicle Number')}}" name="Shipment[vehicle_number]" class="form-control" />

                                </div>
                            </div>
                            <div class="col-md-6">
                               <div class="form-group">
                                    <label>{{translate('Total Docket Number')}}:</label>
                                    <input type="text" placeholder="{{translate('Total Docket Number')}}" name="Shipment[total_docket]" class="form-control" id="total_docket" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vehicle Model')}}:</label>              
                                    <select class="form-control kt-select2 select-manifest" name="Shipment[vehicle_model]" id="manifest_id">
                                        <option value="1">
                                            TATA ACE</option>
                                             <option value="2"> PICKUP</option>
                                              <option value="3">407</option>
                                              <option value="4">407 LPT</option><option value="5">
                                            1109</option>

                                        </option>
                                    </select>
                                   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vehicle Type')}}:</label>                                        
                                    <select class="form-control kt-select2 select-manifest" name="Shipment[vehicle_type]" id="vehicle_type">
                                        <option value="1">6 FEET</option>
                                             <option value="2"> 8FEET</option>
                                              <option value="3">10FEET</option>
                                              <option value="4">14FEET</option><option value="5">
                                            17FEET</option>
                                            <option value="6">
                                            20FEET</option>  <option value="7">
                                            24FEET</option>  <option value="8">
                                            32FEET</option>

                                    </select>

                                   
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Hire Amount')}}:</label>
                                    <input type="text" placeholder="{{translate('Hire Amount')}}" name="Shipment[hire_amount]" class="form-control" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Advance')}}:</label>
                                    <input type="text" placeholder="{{translate('Advance Amount')}}" name="Shipment[advance_amount]" class="form-control" />

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Balance Amount')}}:</label>
                                    <input type="text" placeholder="{{translate('Balance Amount')}}" name="Shipment[balance_amount]" class="form-control" />

                                </div>
                            </div>



                        </div>


                        <div id="kt_repeater_1">

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{translate('Total Package')}}:</label>
                                        <input id="kt_touchspin_3" placeholder="{{translate('Total Package')}}" type="text" min="0" class="form-control" value="0" name="Shipment[total_package]" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{translate('Total Weight')}}:</label>
                                        <input id="kt_touchspin_4" placeholder="{{translate('Total Weight')}}" type="text" min="1" class="form-control total-weight" value="1" name="Shipment[total_weight]" />
                                    </div>
                                </div>

                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label>{{translate('Manifest')}}:</label>
                                    <select class="form-control kt-select2 select-manifest" name="Shipment[manifest_id]" id="manifest_id">
                                        <option></option>
                                        @foreach($manifest as $mn)
                        <option value="{{$mn->id}}">{{$mn->code}}</option>
                                          
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>
                    <div class="mb-0 text-right form-group">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </div>
        </form>

    </div>
</div>

@endsection


@section('script')

<script type="text/javascript">
    function selectIsTriggered()
    {
//         getAdressess(document.getElementById("client-id").value);
    }
    var inputs = document.getElementsByTagName('input');

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type.toLowerCase() == 'number') {
            inputs[i].onkeydown = function(e) {
                if (!((e.keyCode > 95 && e.keyCode < 106) ||
                        (e.keyCode > 47 && e.keyCode < 58) ||
                        e.keyCode == 8)) {
                    return false;
                }
            }
        }
    }

    $('.select-client').select2({
            placeholder: "Select Client",
        })
    {{--@if($user_type == 'admin' || in_array('1005', $staff_permission) )--}}
        {{--.on('select2:open', () => {--}}
            {{--$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.clients.create')}}?redirect=admin.shipments.create"--}}
                {{--class="btn btn-primary" >+ {{translate('Add New Client')}}</a>--}}
                {{--</li>`);--}}
        {{--});--}}
    {{--@endif--}}

    $('.select-client').change(function(){
//        var client_phone = $(this).find(':selected').data('phone');
//        document.getElementById("client_phone").value = client_phone;
    });


    $('.origin').select2({
        placeholder: "Select Origin",
    });
    $('.destination').select2({
            placeholder: "Select Destination",
    });

    $('.select-branch').on('change', function() {
       var dockcount = $("#docket :selected").length;

       $("#total_docket").val(dockcount);
    });
    $('.select-branch').select2({
            placeholder: "Select Docket",
    });
    $('.select-manifest').select2({
            placeholder: "Select Manifest",
    });
    {{--@if($user_type == 'admin' || in_array('1006', $staff_permission) )--}}
        {{--.on('select2:open', () => {--}}
            {{--$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.branchs.create')}}?redirect=admin.shipments.create"--}}
                {{--class="btn btn-primary" >+ {{translate('Add New Docket')}}</a>--}}
                {{--</li>`);--}}
        {{--});--}}
    {{--@endif--}}



    function calcTotalWeight() {
        console.log('sds');
        var elements = $('.weight-listener');
        var sumWeight = 0;
        elements.map(function() {
            sumWeight += parseInt($(this).val());
            console.log(sumWeight);
        }).get();
        $('.total-weight').val(sumWeight);
    }
    $(document).ready(function() {




        $('#kt_datepicker_3').datepicker({
            orientation: "bottom auto",
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: true,
            todayHighlight: true,
            startDate: new Date(),
        });
        $( document ).ready(function() {

        });


        //Package Types Repeater

        $('#kt_repeater_1').repeater({
            initEmpty: false,

            show: function() {
                $(this).slideDown();
                $('.kt_touchspin_weight').TouchSpin({
                    buttondown_class: 'btn btn-secondary',
                    buttonup_class: 'btn btn-secondary',
                    min: 1,
                    max: 1000000000,
                    stepinterval: 50,
                    maxboostedstep: 10000000,
                    initval: 1,
                    prefix: 'Kg'
                });

                calcTotalWeight();
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });


        $('body').on('click', '.delete_item', function(){
            $('.total-weight').val("{{translate('Calculated...')}}");
            setTimeout(function(){ calcTotalWeight(); }, 500);
        });

        $('#kt_touchspin_3').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 0,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '{{currency_symbol()}}'
        });
        $('#kt_touchspin_4').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 1,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            initval: 1,
            prefix: 'Kg'
        });
        $('.kt_touchspin_weight').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 1,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            initval: 1,
            prefix: 'Kg'
        });



        FormValidation.formValidation(
            document.getElementById('kt_form_1'), {
                fields: {

                    "Shipment[date]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[origin]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[client_id]": {
                        validators: {
                            callback: {
                                message: '{{translate("This is required!")}}',
                                callback: function(input) {
                                    // Get the selected options
                                    if ((input.value !== "")) {
                                        $('.client-select').removeClass('has-errors');
                                    } else {
                                        $('.client-select').addClass('has-errors');
                                    }
                                    return (input.value !== "");
                                }
                            }
                        }
                    },

                    "Shipment[boy_name]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[total_docket]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[destination]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[receiver_name]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[amount_to_be_collected]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[total_weight]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Shipment[docket][0]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    }
                },


                plugins: {
                    autoFocus: new FormValidation.plugins.AutoFocus(),
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    // Submit the form when all fields are valid
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    icon: new FormValidation.plugins.Icon({
                        valid: '',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    }),
                }
            }
        );
    });
</script>
@endsection

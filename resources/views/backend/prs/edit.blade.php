@extends('backend.layouts.app')

@section('sub_title'){{translate('Edit PRS')}}@endsection


@section('subheader')
<!--begin::Subheader-->
<div class="py-2 subheader py-lg-6 subheader-solid" id="kt_subheader">
<div class="flex-wrap container-fluid d-flex align-items-center justify-content-between flex-sm-nowrap">
<!--begin::Info-->
<div class="flex-wrap mr-1 d-flex align-items-center">
    <!--begin::Page Heading-->
    <div class="flex-wrap mr-5 d-flex align-items-baseline">
        <!--begin::Page Title-->
        <h5 class="my-1 mr-5 text-dark font-weight-bold">{{ translate('Create PRS') }}</h5>
        <!--end::Page Title-->
        <!--begin::Breadcrumb-->
        <ul class="p-0 my-2 mr-5 breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold font-size-sm">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.dashboard')}}" class="text-muted">{{translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.shipments.index')}}" class="text-muted">{{translate('PRS')}}</a>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="#" class="text-muted">{{ translate('Create PRS') }}</a>
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

$user_type = Auth::user()->user_type;
$staff_permission = json_decode(Auth::user()->staff->role->permissions ?? "[]");
$countries = \App\Country::where('covered',1)->get();
$packages = \App\Package::all();
$deliveryTimes = \App\DeliveryTime::all();

$is_def_mile_or_fees = \App\ShipmentSetting::getVal('is_def_mile_or_fees');
// is_def_mile_or_fees if result 1 for mile if result 2 for fees

$checked_google_map = \App\BusinessSetting::where('type', 'google_map')->first();

if(!$is_def_mile_or_fees){
$is_def_mile_or_fees = 0;
}

if($user_type == 'customer')
{
$user_client = Auth::user()->userClient->client_id;
}
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
<h5 class="mb-0 h6">{{translate('PRS Info')}}</h5>
</div>

@if($user_type == 'admin' || in_array('1105', $staff_permission) )
@if( \App\ShipmentSetting::getVal('def_shipping_cost') == null)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Configure Shipping rates in creation will be zero without configuration')}},
            <a class="alert-link"
               href="{{ route('admin.shipments.settings.fees') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@if(count($countries) == 0 || \App\State::where('covered', 1)->count() == 0)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Configure Your covered countries and cities')}},
            <a class="alert-link"
               href="{{ route('admin.shipments.covered_countries') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@if(\App\Area::count() == 0)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Add areas before creating your first shipment')}},
            <a class="alert-link"
               href="{{ route('admin.areas.create') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@if(count($packages) == 0)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Add package types before creating your first shipment')}},
            <a class="alert-link"
               href="{{ route('admin.packages.create') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@if($branchs->count() == 0)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Add branches before creating your first shipment')}},
            <a class="alert-link"
               href="{{ route('admin.branchs.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif

@if($clients->count() == 0)
    <div class="row">
        <div class="alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
            {{translate('Please Add clients before creating your first shipment')}},
            <a class="alert-link"
               href="{{ route('admin.clients.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@else
@if( \App\ShipmentSetting::getVal('def_shipping_cost') == null || count($countries) == 0 || \App\State::where('covered', 1)->count() == 0 || \App\Area::count() == 0 || count($packages) == 0 || $branchs->count() == 0 || $clients->count() == 0)
    <div class="row">
        <div class="text-center alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;"
             role="alert">
            {{translate('Please ask your administrator to configure shipment settings first, before you can create a new shipment!')}}
        </div>
    </div>
@endif
@endif


<form class="form-horizontal" action="{{ route('admin.prs.update-shipment',['shipment'=>$shipment->id]) }}"
  id="kt_form_1" method="POST" enctype="multipart/form-data">
@csrf
{{ method_field('PATCH') }}
<div class="card-body">
    <div class="row">
        <div class="col-lg-12">


            <hr>
            <div class="row">
                <div class="col-md-6">
                    @if(\App\ShipmentSetting::getVal('is_date_required') == '1' || \App\ShipmentSetting::getVal('is_date_required') == null)
                        <div class="form-group">
                            <label>{{translate(' Date')}}:</label>
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
                                <input type="text" placeholder="{{translate('PRS Date')}}"
                                       value="{{ $shipment->date }}"
                                       name="Shipment[shipping_date]" autocomplete="off"
                                       class="form-control" id="kt_datepicker_3"/>
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
                        <label>{{translate('Vehicle No.')}}:</label>
                        <input type="text" placeholder="{{translate('Vehicle No.')}}" name="Shipment[vehicle_number]"
                               class="form-control" required value="{{ $shipment->vehicle_number }}"/>
                    </div>
                </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vendor Name')}}:</label>
                                    <input type="text" placeholder="{{translate('Vendor Name')}}" name="Shipment[vendor_name]" class="form-control"  value="{{$shipment->vendor_name}}" required/>
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vendor Type')}}:</label>
                                    <select name="Shipment[vendor_type]" class="form-control" required>
                                        <option value="Market" {{$shipment->vendor_type =='Market'? 'selected':''}}>Market</option>
                                        <option value="Attach" {{$shipment->vendor_type =='Attach'? 'selected':''}}>Attach</option>
                                        <option value="Own" {{$shipment->vendor_type =='Own'? 'selected':''}}>Own</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Vehicle Hire Amount')}}:</label>
                                    <input type="text" placeholder="{{translate('Vehicle Hire Amount')}}" name="Shipment[hire_amount]" class="form-control" value="{{$shipment->hire_amount}}" required/>
                                </div>
                            </div>
                              <div class="col-md-6">
                      <div class="form-group">
                                    <label>{{translate('Docket Number')}}:</label>
                                    <?php $selected = explode(",", $shipment->docket);?>
                                    <select class="form-control kt-select2 select-doc" name="Shipment[docket][]" id="docket" multiple>
                                        <option></option>
                                        @foreach($dockets as $docket)
                                        <option value="{{$docket->id}}" {{ (in_array($docket->id, $selected)) ? 'selected' : '' }} >{{$docket->code}}</option>
                                        @endforeach
                                    </select>
                              
                            </div>
                </div>  <div class="col-md-6">
                                <div class="form-group client-select">
                                    <label>{{translate('Sender')}}:</label>                          <input type="text" placeholder="{{translate('Sender Name')}}" name="Shipment[sender_name]" class="form-control"  value="{{$shipment->sender_name}}"/>

                                    
                                </div>
                            </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Receiver Name')}}:</label>
                                    <input type="text" placeholder="{{translate('Receiver Name')}}" name="Shipment[receiver_name]" class="form-control" value="{{$shipment->receiver_name}}" />

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Pick up Boy Name')}}:</label>
                                    <input type="text" placeholder="{{translate('Pick up Boy Name')}}" name="Shipment[boy_name]" class="form-control"  value="{{$shipment->boy_name}}" />

                                </div>
                            </div>
                <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label>{{translate('Total Docket Number')}}:</label>
                                    <input type="text" placeholder="{{translate('Total Docket Number')}}" name="Shipment[total_docket]" class="form-control" id="total_docket" value="{{$shipment->total_docket}}" />

                              
                            </div>
                        </div>
            

              


            </div>
            <hr>

        </div>


        <div class="mb-0 text-right form-group">
            <button type="submit" class="btn btn-sm btn-primary"
                    onclick="get_estimation_cost()">{{translate('Save')}}</button>


        </div>
    </div>
</form>

</div>
</div>

@endsection

@section('script')

<script type="text/javascript">

var inputs = document.getElementsByTagName('input');

for (var i = 0; i < inputs.length; i++) {
if (inputs[i].type.toLowerCase() == 'number') {
    inputs[i].onkeydown = function (e) {
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
@if($user_type == 'admin' || in_array('1005', $staff_permission) )
.on('select2:open', () => {
    $(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.clients.create')}}?redirect=admin.shipments.create"
    class="btn btn-primary" >+ {{translate('Add New Client')}}</a>
    </li>`);
});
@endif

$('.select-client').change(function () {
var client_phone = $(this).find(':selected').data('phone');
document.getElementById("client_phone").value = client_phone;
})


$('.delivery-time').select2({
placeholder: "Delivery Time",
})
@if($user_type == 'admin' || in_array('1110', $staff_permission) )
.on('select2:open', () => {
    $(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.deliveryTime.create')}}?redirect=admin.shipments.create"
    class="btn btn-primary" >+ {{translate('Add Delivery Time')}}</a>
    </li>`);
});
@endif

  $('.select-doc').select2({
        placeholder: "Select Docket",
    });

    $('.select-doc').on('change', function() {
       var dockcount = $("#docket :selected").length;

       $("#total_docket").val(dockcount);
    });
// @if($user_type == 'admin' || in_array('1006', $staff_permission) ).on('select2:open', () => {
//     $(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.branchs.create')}}?redirect=admin.shipments.create"
//     class="btn btn-primary" >+ {{translate('Add New Docket')}}</a>
//     </li>`);
// });

@endif



function calcTotalWeight() {
console.log('sds');
var elements = $('.weight-listener');
var sumWeight = 0;
elements.map(function () {
    sumWeight += parseInt($(this).val());
    console.log(sumWeight);
}).get();
$('.total-weight').val(sumWeight);
}

$(document).ready(function () {


$('#kt_datepicker_3').datepicker({
    orientation: "bottom auto",
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayBtn: true,
    todayHighlight: true,
    startDate: new Date(),
});
$(document).ready(function () {
    $('.package-type-select').select2({
        placeholder: "Package Type",
        language: {
            noResults: function () {
                @if($user_type == 'admin' || in_array('1105', $staff_permission) )
                    return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.packages.create')}}?redirect=admin.shipments.create"
            class="btn btn-primary" >Manage {{translate('Packages')}}</a>
            </li>`;
                @else
                    return ``;
                @endif
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        },
    });
});


//Package Types Repeater

$('#kt_repeater_1').repeater({
    initEmpty: false,

    show: function () {
        $(this).slideDown();


        $('.dimensions_r').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 1,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            initval: 1,
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
        $('.kt_touchspin_qty').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 1,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            initval: 1,
        });
        calcTotalWeight();
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});


$('body').on('click', '.delete_item', function () {
    $('.total-weight').val("{{translate('Calculated...')}}");
    setTimeout(function () {
        calcTotalWeight();
    }, 500);
});
$('#kt_touchspin_2, #kt_touchspin_2_2').TouchSpin({
    buttondown_class: 'btn btn-secondary',
    buttonup_class: 'btn btn-secondary',

    min: -1000000000,
    max: 1000000000,
    stepinterval: 50,
    maxboostedstep: 10000000,
    prefix: '%'
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
$('.kt_touchspin_qty').TouchSpin({
    buttondown_class: 'btn btn-secondary',
    buttonup_class: 'btn btn-secondary',

    min: 1,
    max: 1000000000,
    stepinterval: 50,
    maxboostedstep: 10000000,
    initval: 1,
});
$('.dimensions_r').TouchSpin({
    buttondown_class: 'btn btn-secondary',
    buttonup_class: 'btn btn-secondary',

    min: 1,
    max: 1000000000,
    stepinterval: 50,
    maxboostedstep: 10000000,
    initval: 1,
});


FormValidation.formValidation(
    document.getElementById('kt_form_1'), {
        fields: {
            "Shipment[vehicle_number]": {
                validators: {
                    notEmpty: {
                        message: '{{translate("This is required!")}}'
                    }
                }
            },
            "Shipment[date]": {
                validators: {
                    notEmpty: {
                        message: '{{translate("This is required!")}}'
                    }
                }
            },
            "Shipment[vendor_name]": {
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
                        callback: function (input) {
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
            "Shipment[docket][0]": {
                validators: {
                    notEmpty: {
                        message: '{{translate("This is required!")}}'
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
            "Shipment[hire_amount]": {
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

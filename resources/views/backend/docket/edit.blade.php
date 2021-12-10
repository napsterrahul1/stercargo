@php

$staff_permission = json_decode(Auth::user()->staff->role->permissions ?? "[]");
@endphp

@extends('backend.layouts.app')

@section('content')

<div class="mx-auto col-lg-12">
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Docket Charges')}}</h5>
    </div>
<form class="form-horizontal" action="{{ route('dockets.update',[$client->id]) }}" id="kt_form_1" method="POST" enctype="multipart/form-data">
@csrf
            {{ method_field('PATCH') }}

        {!!redirect_input()!!}
        <div class="card-body ">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Docket No')}}:</label>
                        <input type="text" id="code" class="form-control"
                               placeholder="{{translate('Docket No')}}" name="code" value="{{$client->code}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Date')}}:</label>
                        {{--<input type="text" id="date" class="form-control" placeholder="{{translate('Date')}}" name="date">--}}
                        <input type="text" placeholder="{{translate('Date')}}" name="date" autocomplete="off"
                               class="form-control" id="kt_datepicker_3" value="{{$client->date}}"/>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Invoice No')}}:</label>
                        <input type="text" id="invoice_no" class="form-control"
                               placeholder="{{translate('Invoice No')}}" name="invoice_no" value="{{$client->invoice_no}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Invoice Value')}}:</label>
                        <input type="text" id="invoice_no" class="form-control"
                               placeholder="{{translate('Invoice Value')}}" name="invoice_value" value="{{$client->invoice_value}}">
                    </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>{{translate('Source')}}:</label>
                    <select name="from_source" style="display: block !important;"
                            class="change-area-client-address form-control">
                        <option value="">Select</option>
                        <?php foreach ($areas as $key => $value): ?>
                        <option value="{{$value->id}}" {{$value->id == $client->from_source ? 'selected':''}}>{{$value->name}}</option>
                        <?php endforeach ?>

                    </select>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{translate('Destination')}}:</label>
                    <select name="to_destination" style="display: block !important;"
                            class="change-area-client-address form-control">
                        <option value="">Select</option>
                        <?php foreach ($areas as $key => $value): ?>
                        <option value="{{$value->id}}" {{$value->id == $client->to_destination ? 'selected':''}}>{{$value->name}}</option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">

            <div class="form-group">
                <label>{{translate('Freight Paid By')}}:</label>
                <input id="freight_paid" type="text" class="form-control"
                placeholder="{{translate('Freight Paid By')}}" name="freight_paid_by" value="{{$client->freight_paid_by}}">
            </div>
            </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Sender')}}:</label>
                        <input type="text" class="form-control" id="sender"
                               placeholder="{{translate('sender')}}" name="sender" value="{{$client->sender}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Receiver')}}:</label>
                        <input type="text" class="form-control" placeholder="{{translate('Receiver')}}"
                               name="receiver" value="{{$client->receiver}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Billing Party')}}:</label>
                        <input type="text" class="form-control" id="billing_party"
                               placeholder="{{translate('Billing Party')}}" name="billing_party" value="{{$client->billing_party}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Product')}}:</label>
                        <input type="text" class="form-control" placeholder="{{translate('Product')}}"
                               name="product" value="{{$client->product}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Description')}} :</label>
                        <input type="text" class="form-control" id="description"
                               placeholder="{{translate('Description')}}" name="description" value="{{$client->description}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Bill Paid By')}}:</label>
                        <select name="bill_paid_by" style="display: block !important;"
                                class="change-area-client-address form-control">
                            <option value="0" {{$client->bill_paid_by == 0 ? 'selected':''}}>Sender</option>
                            <option value="1" {{$client->bill_paid_by == 1 ? 'selected':''}}>Receiver</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('PCS')}}:</label>
                        <input type="text" class="form-control fright" placeholder="{{translate('PCS')}}" name="pcs" id="pcs" value="{{$client->pcs}}">
                    </div>
                </div>
                <div class="col-md-3">

            <div class="form-group">
                <label>{{translate('Actual Weight')}}:</label>
                <input type="text" class="form-control fright" placeholder="{{translate('Actual Weight')}}" name="actual_weight" value="{{$client->actual_weight}}">
            </div>
            </div>
                <div class="col-md-3">
            <div class="form-group">
                <label>{{translate('Charge Weight')}}:</label>
                <input type="text" class="form-control fright" placeholder="{{translate('Charge Weight')}}"
                       name="charge_weight" id="charge_weight" value="{{$client->charge_weight}}">
            </div>
            </div>
                <div class="col-md-6">
            <div class="form-group">
                <label>{{translate('Varai/Hamali Charge/Loading Unloading Charge')}}:</label>
                <input type="text" class="form-control" placeholder="{{translate('Charge')}}" name="charge" value="{{$client->charge}}">
            </div>
            </div>
                <div class="col-md-3">
            <div class="form-group">
                <label>{{translate('Calculation On')}}:</label>
                {{--<input type="text" class="form-control" placeholder="{{translate('Calc on')}}" name="">--}}
                <select name="calc_on" style="display: block !important;"
                        class="change-area-client-address form-control" id="calc_on">
                    <option value="">Select</option>
                    <option value="PCS" {{$client->calc_on == "PCS" ? 'selected':''}}>PCS</option>
                    <option value="Weight" {{$client->calc_on == "Weight" ? 'selected':''}}>Weight</option>
                    <option value="FTL" {{$client->calc_on == "FTL" ? 'selected':''}}>FTL</option>

                </select>
            </div>
            </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Freight Rate')}}:</label>
                        <input type="number" class="form-control fright" placeholder="{{translate('Freight Rate')}}" name="freight_rate" id="freight_rate" value="{{$client->freight_rate}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Freight Amount')}}:</label>
                        <input type="number" class="form-control"  placeholder="{{translate('Freight Amount')}}" name="freight_amount" id="freight_amount" value="{{$client->freight_amount}}" readonly>
                    </div>
                </div>
                     <div class="col-md-3">
            <div class="form-group">
                <label>{{translate('FOV')}}:</label>
                <input type="number" class="form-control" placeholder="{{translate('FOV')}}" name="FOV" min="0.0" max="100" id="fov" value="{{$client->FOV}}">
            </div>
            </div>
                <div class="col-md-3">
            <div class="form-group">
                <label>{{translate('Fuel')}}:</label>
                <input type="number" class="form-control" min="0.0" max="100" placeholder="{{translate('Fuel')}}" name="fuel" id="fuel" value="{{$client->fuel}}">
            </div>
            </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('FOV Charges')}}:</label>
                        <input type="number" id="fov_charges" readonly class="form-control"  placeholder="{{translate('FOV Charges')}}"  name="fov_charges" value="{{$client->fov_charges}}" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Total Amount')}}:</label>
                        <input type="number" id="total_amount" readonly class="form-control finalamount"  placeholder="{{translate('Total Amount')}}"  name="total_amount" value="{{$client->total_amount}}" readonly>
                    </div>
                </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Fuel Charges')}}:</label>
                        <input type="number" id="fuel_charges" readonly class="form-control finalamount"  placeholder="{{translate('Fuel Charges')}}"  name="fuel_charges" value="{{$client->fuel_charges}}" readonly>
                    </div>
                </div>
            </div>



                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{translate('LR Charge')}}({{currency_symbol()}}):</label>
              <input type="number" min="0" class="form-control finalamount" placeholder="{{translate('Here')}}" name="LR_charge" id="lr_charges" value="{{$client->LR_charge}}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{translate('ODA Charge')}}({{currency_symbol()}}):</label>
    <input type="number" min="0" class="form-control finalamount" placeholder="{{translate('Here')}}" name="oda_charge" id="oda_charge" value="{{$client->oda_charge}}">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{translate('Door Delivery Charge')}}:</label>
                            <input type="number" min="0" id="door_dly_charge" class="form-control finalamount"
                                   placeholder="{{translate('door_dly_charge')}}"
                                   name="door_dly_charge" value="{{$client->door_dly_charge}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{translate('Final Amount')}}:</label>
                            <input type="number" min="0" id="final_amount" class="form-control"
                                   placeholder="{{translate('Final amount')}}" 
                                   name="final_amount" value="{{$client->final_amount}}" readonly>
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
//        $('#final_amount').TouchSpin({
//            buttondown_class: 'btn btn-secondary disabled',
//            buttonup_class: 'btn btn-secondary disabled',
//            min: 1,
//            max: 1000000000,
//            stepinterval: 50,
//            maxboostedstep: 10000000,
//            initval: 1,
//            prefix: '$',
//            disabled:true
//        });
//        $('#total_amount').TouchSpin({
//            buttondown_class: 'btn btn-secondary',
//            buttonup_class: 'btn btn-secondary',
//            min: 1,
//            max: 1000000000,
//            stepinterval: 50,
//            maxboostedstep: 10000000,
//            initval: 1,
//            prefix: '$'
//        });

$('#kt_datepicker_3').datepicker({
    orientation: "bottom auto",
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayBtn: true,
    todayHighlight: true,
    startDate: new Date(),
});

$(document).ready(function () {

    FormValidation.formValidation(
        document.getElementById('kt_form_1'), {
            fields: {
                "invoice_no": {
                    validators: {
                        notEmpty: {
                            message: '{{translate("This is required!")}}'
                        }
                    }
                },
                "date": {
                    validators: {
                        notEmpty: {
                            message: '{{translate("This is required!")}}'
                        },
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
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            }
        }
    );
});



    function getcalon(calc_on) {
        if(calc_on == 'FTL'){
        var pcs = 1;

        }else if(calc_on == "Weight"){
        var pcs = $('#charge_weight').val();

        }else{
        var pcs = $('#pcs').val();

        }
        var freight_rate = $('#freight_rate').val();
        var amount = pcs * freight_rate;
        $("#freight_amount").val('');
        $("#freight_amount").val(amount);
    }

    $(".fright").on('change keyup paste', function() {
    var calc_on = $("#calc_on").val();

    getcalon(calc_on);
    });

    $("#calc_on").on('change', function() {
     //alert(1);
     var calc_on = $(this).val();
    getcalon(calc_on);
    });
    


$('#fov').on('input', function() {
calculatefov();
calculatefuel();
calculatefinalamount();

});
$('#freight_amount').on('input', function() {
calculatefov();
calculatefuel();
calculatefinalamount();

});
function calculatefov(){
var pPos = parseInt($('#freight_amount').val()); 
var pEarned = parseInt($('#fov').val());
var perc="";
if(isNaN(pPos) || isNaN(pEarned)){
    perc=" ";
   }else{
   perc = ((pEarned/100) * pPos).toFixed(3);
   }

$('#fov_charges').val(perc);
var fov_charge = parseInt($('#fov_charges').val());


var total = pPos + fov_charge;
$('#total_amount').val(total);
}
function calculatefuel(){
var pPos = parseInt($('#total_amount').val()); 
var pEarned = parseInt($('#fuel').val());
var perc="";
if(isNaN(pPos) || isNaN(pEarned)){
    perc=" ";
   }else{
   perc = ((pEarned/100) * pPos).toFixed(3);
   }

$('#fuel_charges').val(perc);
}

$('#fuel').on('input', function() {
calculatefuel();
});

function calculatefinalamount(){
var total_amount = parseInt($('#total_amount').val()); 
var fuel_charges = parseInt($('#fuel_charges').val());
var lr_charges = parseInt($('#lr_charges').val());
var oda_charge = parseInt($('#oda_charge').val());
var door_charges = parseInt($('#door_dly_charge').val());
var perc="";
if(isNaN(fuel_charges) || isNaN(lr_charges) || isNaN(oda_charge) || isNaN(door_charges) || isNaN(total_amount)){
    perc=" ";
   }else{
   perc = total_amount + fuel_charges + lr_charges + oda_charge + door_charges;
   }

$('#final_amount').val(perc);
}

    $(".finalamount").on('change keyup paste', function() {
    calculatefinalamount();
    });




</script>
@endsection

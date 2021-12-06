@php
    $checked_google_map = \App\BusinessSetting::where('type', 'google_map')->first();
    $is_def_mile_or_fees = \App\ShipmentSetting::getVal('is_def_mile_or_fees');
    $countries = \App\Country::where('covered',1)->get();
    $user_type = Auth::user()->user_type;
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
            {{ method_field('PATCH') }}
                        @csrf

            <div class="card-body">
                <div class="form-group">
                    <label>{{translate('Invoice No')}}:</label>
                    <input type="text" id="invoice_no" class="form-control" placeholder="{{translate('Invoice No')}}" name="invoice_no" value="{{$client->invoice_no}}">
                </div>  
                <div class="form-group">
                    <label>{{translate('Date')}}:</label>
                     <input type="text" placeholder="{{translate('Date')}}" name="date" autocomplete="off" class="form-control" id="kt_datepicker_3" value="{{$client->date}}"/>

                </div>
                <div class="form-group">
                    <label>{{translate('Source')}}:</label>                                 
                <select name="from_source" style="display: block !important;" class="change-area-client-address form-control">
                    <option value="">Select</option>
                    <?php foreach ($areas as $key => $valuee): ?>
                        <option value="{{$valuee->id}}"  <?php echo $client->from_source == $valuee->id?'selected':'' ?>>{{$valuee->name}}</option>

                    <?php endforeach ?>

                     </select>

                </div> <div class="form-group">
                    <label>{{translate('Destination')}}:</label>
                <select name="to_destination" style="display: block !important;" class="change-area-client-address form-control">
                    <option value="">Select</option>
                    <?php foreach ($areas as $key => $value): ?>
                        <option value="{{$value->id}}" <?php echo $client->to_destination == $value->id?'selected':'' ?>>{{$value->name}}</option>

                    <?php endforeach ?>

                </select>


                </div> <div class="form-group" value="{{$client->to_destination}}">
                    <label>{{translate('Freight Paid By')}}:</label>
                    <input id="destination" type="text" class="form-control" placeholder="{{translate('Freight Paid By')}}" name="freight_paid _by" value="{{$client->freight_paid_by}}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Sender')}}:</label>
                            <input type="text" class="form-control" id="sender" placeholder="{{translate('sender')}}" name="sender" value="{{$client->sender}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Receiver')}}:</label>
                            <input type="text" class="form-control" placeholder="{{translate('Receiver')}}" name="receiver" value="{{$client->receiver}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Billing Party')}}:</label>
                            <input type="text" class="form-control" id="billing_party" placeholder="{{translate('Billing Party')}}" name="billing_party" value="{{$client->billing_party}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Product')}}:</label>
                            <input type="text" class="form-control" placeholder="{{translate('Product')}}" name="product" value="{{$client->product}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('Description')}} :</label>
                            <input type="text" class="form-control" id="description" placeholder="{{translate('Description')}}" name="description" value="{{$client->description}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{translate('PCS')}}:</label>
                            <input type="text" class="form-control" placeholder="{{translate('PCS')}}" name="pcs" value="{{$client->pcs}}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{translate('Actual Weight')}}:</label>
                    <input type="text" class="form-control" placeholder="{{translate('Actual Weight')}}" name="actual_weight" value="{{$client->actual_weight}}">
                </div>
                <div class="form-group">
                    <label>{{translate('Charge Weight')}}:</label>
                    <input type="text" class="form-control" placeholder="{{translate('Charge Weight')}}" name="charge_weight" value="{{$client->charge_weight}}">
                </div>
                <div class="form-group">
                    <label>{{translate('Varai/Hamali Charge/Loading Unloading Charge')}}:</label>
                    <input type="text" class="form-control" placeholder="{{translate('Charge')}}" name="charge" value="{{$client->charge}}">
                </div>
                  <div class="form-group">
                    <label>{{translate('Calculation On')}}:</label>
                    <input type="text" class="form-control" placeholder="{{translate('Calc on')}}" name="calc_on" value="{{$client->calc_on}}">
                </div>

                <div class="form-group">
                    <label>{{translate('Bill Paid By')}}:</label>
                      <select name="bill_paid_by" style="display: block !important;" class="change-area-client-address form-control">
                        <option value="0" <?php echo $client->bill_paid_by == 0?'selected':'' ?>>Sender</option>
                        <option value="1" <?php echo $client->bill_paid_by == 1?'selected':'' ?>>Receiver</option>

                     </select>
                </div>
                <div class="form-group">
                    <label>{{translate('FOV')}}:</label>
                    <input type="number" class="form-control" placeholder="{{translate('FOV')}}" name="FOV" min="0.1" max="0.2" value="{{$client->FOV}}">
                </div>
                <div class="form-group">
                    <label>{{translate('Fuel')}}:</label>
                    <input type="number" class="form-control" placeholder="{{translate('Fuel')}}" name="fuel" value="{{$client->fuel}}">
                </div>

                
                <div class="mt-5 card">
                   
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{translate('LR_charge')}}({{currency_symbol()}}):</label>
                                    <input type="number" min="0"  class="form-control" placeholder="{{translate('Here')}}" name="LR_charge" value="{{$client->LR_charge}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{translate('oda_charge')}}({{currency_symbol()}}):</label>
                                    <input type="number" min="0"  class="form-control" placeholder="{{translate('Here')}}" name="oda_charge" value="{{$client->oda_charge}}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mt-5 card">
                   
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">


                                <div class="row">
                                    
                               

                                    <div class="form-group col-md-4">
                                        <label>{{translate('Door Dly Charge')}}:</label>
                                        <input type="number" min="0" id="door_dly_charge" class="form-control" placeholder="{{translate('door_dly_charge')}}"  name="door_dly_charge" value="{{$client->door_dly_charge}}">
                                    </div>
                               
                                   
                                </div>
                                <hr>


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
<script src="{{ static_asset('assets/dashboard/js/geocomplete/jquery.geocomplete.js') }}"></script>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{$checked_google_map->key}}"></script>

<script type="text/javascript">

   
    $('.how-know-us').select2({
        placeholder: "Client Source",
    });
   
    $('#kt_datepicker_3').datepicker({
            orientation: "bottom auto",
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: true,
            todayHighlight: true,
            startDate: new Date(),
        });
  

    $(document).ready(function() {

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
                    "from_source": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            },
                        }
                    },
                    "to_destination": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            },
                           
                        }
                    },
                    "freight_paid_by": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            },
                        }
                    },
                    "product": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "description": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("Client Branch is required!")}}'
                            }
                        }
                    },
                    "pcs": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    }, 
                    "actual_weight": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "charge_weight": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "calc_on": {
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
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    }),
                }
            }
        );
    });
</script>
@endsection

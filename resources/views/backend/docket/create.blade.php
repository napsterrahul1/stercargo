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

            <form class="form-horizontal" action="{{ route('dockets.store') }}" id="kt_form_1" method="POST"
                  enctype="multipart/form-data">
                @csrf
                {!!redirect_input()!!}
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Docket No')}}:</label>
                                <input type="text" id="code" class="form-control"
                                       placeholder="{{translate('Docket No')}}" name="code">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Date')}}:</label>
                                {{--<input type="text" id="date" class="form-control" placeholder="{{translate('Date')}}" name="date">--}}
                                <input type="text" placeholder="{{translate('Date')}}" name="date" autocomplete="off"
                                       class="form-control" id="kt_datepicker_3"/>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Invoice No')}}:</label>
                                <input type="text" id="invoice_no" class="form-control"
                                       placeholder="{{translate('Invoice No')}}" name="invoice_no">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Invoice Value')}}:</label>
                                <input type="text" id="invoice_no" class="form-control"
                                       placeholder="{{translate('Invoice Value')}}" name="invoice_value">
                            </div>
                        </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{translate('Source')}}:</label>
                            <select name="from_source" style="display: block !important;"
                                    class="change-area-client-address form-control">
                                <option value="">Select</option>
                                <?php foreach ($areas as $key => $value): ?>
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                <?php endforeach ?>

                            </select>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{translate('Destination')}}:</label>
                            <select name="destination" style="display: block !important;"
                                    class="change-area-client-address form-control">
                                <option value="">Select</option>
                                <?php foreach ($areas as $key => $value): ?>
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">

                    <div class="form-group">
                        <label>{{translate('Freight Paid By')}}:</label>
                        <input id="destination" type="text" class="form-control"
                               placeholder="{{translate('Freight Paid By')}}" name="freight_paid _by">
                    </div>
                    </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Sender')}}:</label>
                                <input type="text" class="form-control" id="sender"
                                       placeholder="{{translate('sender')}}" name="sender">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Receiver')}}:</label>
                                <input type="text" class="form-control" placeholder="{{translate('Receiver')}}"
                                       name="receiver">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Billing Party')}}:</label>
                                <input type="text" class="form-control" id="billing_party"
                                       placeholder="{{translate('Billing Party')}}" name="billing_party">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Product')}}:</label>
                                <input type="text" class="form-control" placeholder="{{translate('Product')}}"
                                       name="product">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Description')}} :</label>
                                <input type="text" class="form-control" id="description"
                                       placeholder="{{translate('Description')}}" name="description">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('PCS')}}:</label>
                                <input type="text" class="form-control" placeholder="{{translate('PCS')}}" name="pcs">
                            </div>
                        </div>
                        <div class="col-md-3">

                    <div class="form-group">
                        <label>{{translate('Actual Weight')}}:</label>
                        <input type="text" class="form-control" placeholder="{{translate('Actual Weight')}}"
                               name="actual_weight">
                    </div>
                    </div>
                        <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Charge Weight')}}:</label>
                        <input type="text" class="form-control" placeholder="{{translate('Charge Weight')}}"
                               name="charge_weight">
                    </div>
                    </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Bill Paid By')}}:</label>
                                <select name="bill_paid_by" style="display: block !important;"
                                        class="change-area-client-address form-control">
                                    <option value="0">Sender</option>
                                    <option value="1">Receiver</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                    <div class="form-group">
                        <label>{{translate('Varai/Hamali Charge/Loading Unloading Charge')}}:</label>
                        <input type="text" class="form-control" placeholder="{{translate('Charge')}}" name="charge">
                    </div>
                    </div>
                        <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Calculation On')}}:</label>
                        {{--<input type="text" class="form-control" placeholder="{{translate('Calc on')}}" name="">--}}
                        <select name="calc_on" style="display: block !important;"
                                class="change-area-client-address form-control">
                            <option value="PCS">PCS</option>
                            <option value="Weight">Weight</option>
                            <option value="FTL">FTL</option>

                        </select>
                    </div>
                    </div>

                        <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('FOV')}}:</label>
                        <input type="number" class="form-control" placeholder="{{translate('FOV')}}" name="FOV"
                               min="0.0" max="100">
                    </div>
                    </div>
                        <div class="col-md-3">
                    <div class="form-group">
                        <label>{{translate('Fuel')}}:</label>
                        <input type="number" class="form-control" min="0.0" max="100" placeholder="{{translate('Fuel')}}" name="fuel">
                    </div>
                    </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Freight Rate')}}:</label>
                                <input type="number" class="form-control" placeholder="{{translate('Freight Rate')}}" name="freight_rate">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Freight Amount')}}:</label>
                                <input type="number" class="form-control"  placeholder="{{translate('Freight Amount')}}" name="freight_amount">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{translate('Total Amount')}}:</label>
                                <input type="number" id="total_amount" readonly class="form-control"  placeholder="{{translate('Total Amount')}}" value="0.0" name="fuel">
                            </div>
                        </div>
                    </div>



                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>{{translate('LR Charge')}}({{currency_symbol()}}):</label>
                                        <input type="number" min="0" value="" class="form-control"
                                               placeholder="{{translate('Here')}}" name="LR_charge">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>{{translate('ODA Charge')}}({{currency_symbol()}}):</label>
                                        <input type="number" min="0" value="" class="form-control"
                                               placeholder="{{translate('Here')}}" name="oda_charge">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>{{translate('Door Delivery Charge')}}:</label>
                                    <input type="number" min="0" id="door_dly_charge" class="form-control"
                                           placeholder="{{translate('door_dly_charge')}}" value=""
                                           name="door_dly_charge">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>{{translate('Final Amount')}}:</label>
                                    <input type="number" min="0" id="final_amount" class="form-control"
                                           placeholder="{{translate('Final amount')}}" value="0.0"
                                           name="final_amount" readonly>
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
    </script>
@endsection

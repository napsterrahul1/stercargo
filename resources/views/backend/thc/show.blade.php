<?php
use \Milon\Barcode\DNS1D;
$d = new DNS1D();
?>
@extends('backend.layouts.app')

@section('sub_title'){{translate('Loading Sheet')}} {{$shipment->code}}@endsection
@section('subheader')
    <!--begin::Subheader-->
    <div class="py-2 subheader py-lg-6 subheader-solid" id="kt_subheader">
        <div class="flex-wrap container-fluid d-flex align-items-center justify-content-between flex-sm-nowrap">
            <!--begin::Info-->
            <div class="flex-wrap mr-1 d-flex align-items-center">
                <!--begin::Page Heading-->
                <div class="flex-wrap mr-5 d-flex align-items-baseline">
                    <!--begin::Page Title-->
                    <h5 class="my-1 mr-5 text-dark font-weight-bold">{{translate('Loading Sheet')}} {{$shipment->code}}</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="p-0 my-2 mr-5 breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard')}}" class="text-muted">{{translate('Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted">{{$shipment->code}}</a>
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



    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="p-0 card-body">
            <!-- begin: Invoice-->
            <!-- begin: Invoice header-->
            <div class="px-8 py-8 row justify-content-center pt-md-27 px-md-0">
                <div class="col-md-10">
                    <div class="pb-10 d-flex justify-content-between pb-md-20 flex-column flex-md-row">
                        <div class="px-0 d-flex flex-column align-items-md-start">
                        <span class="d-flex flex-column align-items-md-start">
                            <h1 class="mb-10 display-4 font-weight-boldest">{{translate('Loading Sheet')}}: {{$shipment->code}}</h1>
                            @if($shipment->order_id != null)
                                <span><span class="font-weight-bolder opacity-70">{{translate('Order ID')}}:</span> {{$shipment->order_id}}</span>
                            @endif
                        </span>
                        </div>
                        <div class="px-0 d-flex flex-column align-items-md-end">
                        <span class="d-flex flex-column align-items-md-end opacity-70">
                            @if($shipment->barcode != null)
                                <span class="mb-5 font-weight-bolder"><?=$d->getBarcodeHTML($shipment->code, "C128");?></span>
                            @endif

                        </span>
                        </div>
                    </div>

                    <div class="pb-6 d-flex justify-content-between">
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-4 text-dark font-weight-bold">{{translate('Customer/Sender')}}</span>
                            @if(Auth::user()->user_type == 'admin' || in_array('1005', json_decode(Auth::user()->staff->role->permissions ?? "[]")))
                                <a class="text-danger font-weight-boldest font-size-lg" href="{{route('admin.clients.show',$shipment->client_id)}}">{{$shipment->client->name}}</a>
                            @else
                                <span class="text-danger font-weight-boldest font-size-lg">{{$shipment->client->name}}</span>
                            @endif

                        </div>
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-4 text-dark font-weight-bold">{{translate('Receiver')}}</span>
                            <span class="text-danger font-weight-boldest font-size-lg">{{$shipment->receiver_name}}</span>

                        </div>
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-4 text-dark font-weight-bold">{{translate('Status')}}</span>
                            <span class="opacity-70 d-block">{{$shipment->getStatus()}}</span>
                        </div>
                        @if ($shipment->amount_to_be_collected && $shipment->amount_to_be_collected  > 0)
                            <div class="d-flex flex-column flex-root">
                                <span class="mb-4 text-dark font-weight-bold">{{translate('Amount To Collected')}}</span>
                                <span class="text-muted font-weight-bolder font-size-lg">{{format_price($shipment->amount_to_be_collected)}}</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-bottom w-100"></div>
                    <div class="pt-6 d-flex justify-content-between">
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-2 font-weight-bolder">{{translate('Origin')}}</span>
                            <span class="opacity-70">{{$shipment->origins->name}}</span>
                        </div>
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-2 font-weight-bolder">{{translate('Destination')}}</span>
                            <span class="opacity-70">{{$shipment->destinations->name }}</span>
                        </div>


                        <div class="d-flex flex-column flex-root">
                            <span class="mb-2 font-weight-bolder">{{translate('Created date')}}</span>
                            <span class="opacity-70">{{$shipment->created_at->format('d-m-Y h:i:s')}}</span>
                        </div>
                        <div class="d-flex flex-column flex-root">
                            <span class="mb-2 font-weight-bolder">{{translate('Shipping date')}}</span>
                            <span class="opacity-70">{{\Carbon\Carbon::parse($shipment->date)->format('d-m-Y')}}</span>
                        </div>
                    </div>


                    <div class="pt-6 d-flex justify-content-between">

                        <div class="d-flex flex-column flex-root">
                            <span class="mb-4 text-dark font-weight-bold">{{translate('Total Weight')}}</span>
                            <span class="text-muted font-weight-bolder font-size-lg">{{$shipment->total_weight}} {{translate('KG')}}</span>
                        </div>

                    </div>

                    <div class="pt-6 d-flex justify-content-between">

                    </div>


                    <div class="pt-6 d-flex justify-content-between">

                    </div>
                    @if($shipment->attachments_before_shipping)

                    @endif


                </div>
            </div>
            <!-- end: Invoice header-->
            <!-- begin: Invoice body-->
            <div class="px-8 py-8 row justify-content-center py-md-10 px-md-0">
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="pl-0 font-weight-bold text-muted text-uppercase">{{translate('Package ')}}</th>

                                <th class="pr-0 text-right font-weight-bold text-muted text-uppercase">{{translate('Weight')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($shipment->packages as $package)

                                <tr class="font-weight-boldest">
                                    <td class="pl-0 border-0 pt-7 d-flex align-items-center">{{$package->description}}</td>

                                    <td class="pr-0 text-right align-middle text-primary pt-7">{{$package->weight." ".translate('KG') }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- end: Invoice body-->
            <!-- begin: Invoice footer-->

            <!-- end: Invoice footer-->
            <!-- begin: Invoice action-->
            <div class="px-8 py-8 row justify-content-center py-md-10 px-md-0">
                <div class="col-md-10">
                    <div class="d-flex justify-content-between">


                        <a href="{{route('admin.thc.print', array($shipment->id, 'label'))}}" class="btn btn-light-primary font-weight-bold" target="_blank">{{translate('Print Label')}}<i class="ml-2 la la-box-open"></i></a>
                        <a href="{{route('admin.thc.print', array($shipment->id, 'invoice'))}}" class="btn btn-light-primary font-weight-bold" target="_blank">{{translate('Print Invoice')}}<i class="ml-2 la la-file-invoice-dollar"></i></a>

                        @if(Auth::user()->user_type == 'admin')
                            <a href="{{route('admin.thc.edit', $shipment->id)}}" class="px-6 py-3 btn btn-light-info btn-sm font-weight-bolder font-size-sm">{{translate('Edit Loading Sheet')}}</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- end: Invoice action-->
            <!-- end: Invoice-->
        </div>
    </div>
    <!--end::Card-->


    <!--end::List Widget 19-->
    @if((Auth::user()->user_type == 'admin'))

    @endif

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            AIZ.plugins.notify('success', '{{translate("Payment Link Copied")}}');
        }
    </script>
@endsection

@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Dockets')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('dockets.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Docket')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Dockets')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th  width="3%">#</th>
                    <th >{{translate('Invoice NO.')}}</th>
                    <th >{{translate('Date')}}</th>
                    <th >{{translate('Source')}}</th>
                    <th >{{translate('Destiantion')}}</th>
                    
                    <th  width="10%" class="text-center">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                <?php $sr = 1;?>
                @foreach($clients as $key => $client)
                        <tr><td>{{$sr}}</td>
                            <td  width="3%">{{ $client->invoice_no }}</td>
                            <td width="20%">{{$client->date}}</td>
                            <td width="20%">{{$client->cities->name}}</td>
                            <td width="20%">{{$client->destinations->name}}</td>
                           
                            <td class="text-center">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('dockets.show', $client->id)}}" title="{{ translate('Show') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('dockets.edit', $client->id)}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('dockets.delete-docket', [$client->id])}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                        </tr>
               <?php $sr++; ?>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $clients->appends(request()->input())->links() }}
        </div>
    </div>
</div>
{!! hookView('spot-cargo-shipment-client-addon',$currentView) !!}

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

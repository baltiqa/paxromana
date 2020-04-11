@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Buy Logs</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/buyerlogs" class="breadcrumb-link">Buy Logs</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Buy logs</h5>
                <div class="card-body">
                    <a href="{{route('panel.withdraw.xmr',['amount'=>$xmr_amount,'state'=>1])}}" class="btn btn-primary" type="submit">XMR Cashout {{$xmr_amount}}</a>
                    <a href="{{route('panel.withdraw.btc',['amount'=>$btc_amount,'state'=>1])}}"  class="btn btn-primary" type="submit">BTC Cashout {{$btc_amount}}</a>
                    <a href="{{route('panel.withdraw.ltc',['amount'=>$ltc_amount,'state'=>1])}}" class="btn btn-primary" type="submit">LTC Cashout {{$ltc_amount}}</a>
                    @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th class="border-0">Created At</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Username</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Cashed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->username}}</td>
                                <td>{{$item->amount}} {{$item->currency}}</td>
                                <td>{{$item->paid == 1 ? 'Paid out' : 'Not paid out'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>





@stop

@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Regular Escrow</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/regularescrow" class="breadcrumb-link">Regular Escrow</a>
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
                <h5 class="card-header">Regular Escrow</h5>
                <div class="card-body">
                <a href="{{route('panel.withdraw.xmr',['amount'=>$xmr_amount,'state'=>2])}}" class="btn btn-primary" type="submit">XMR Cashout {{$xmr_amount}}</a>
                <a href="{{route('panel.withdraw.btc',['amount'=>$btc_amount,'state'=>2])}}"  class="btn btn-primary" type="submit">BTC Cashout {{$btc_amount}}</a>
                <a href="{{route('panel.withdraw.ltc',['amount'=>$ltc_amount,'state'=>2])}}" class="btn btn-primary" type="submit">LTC Cashout {{$ltc_amount}}</a>
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th class="border-0">Created At</th>
                                <th class="border-0">Order</th>
                                <th class="border-0">Seller</th>
                                <th class="border-0">Service Fee</th>
                                <th class="border-0">Referral</th>
                                <th class="border-0">Cashed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->id}}</td>
                                <td>{{$item->seller->username}}</td>
                                <td>{{number_format($item->service_fee,12)}} {{$item->currency}}</td>
                                <td>{{$item->reff == null ? 'No' : 'Yes'}}</td>
                                <td>{{$item->is_paid == 1 ? 'Paid out' : 'Not paid out'}}</td>
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

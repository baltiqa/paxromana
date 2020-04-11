@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Multisig Escrows</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/multisigescrow" class="breadcrumb-link">Multisig Escrows</a>
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
                <h5 class="card-header">Multisig Escrows</h5>
                <div class="card-body">
                    <button class="btn btn-primary" disabled>XMR Stats {{number_format($xmr_amount,10)}}</button>
                            <button class="btn btn-primary" disabled>BTC Stats {{number_format($btc_amount,12)}}</button>
                           <button class="btn btn-primary" disabled>LTC Stats {{number_format($ltc_amount,10)}}</button>

                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th class="border-0">Created At</th>
                                <th class="border-0">Order</th>
                                <th class="border-0">Seller</th>
                                <th class="border-0">Service Fee</th>
                                <th class="border-0">Referral</th>
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

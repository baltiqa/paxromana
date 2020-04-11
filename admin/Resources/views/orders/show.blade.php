@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Orders</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/orders" class="breadcrumb-link">Orders</a>
                            <li class="breadcrumb-item"><a href="/panel/orders/{{$order->hash}}" class="breadcrumb-link">Order #{{$order->id}}</a>
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
                <h5 class="card-header">Orders</h5>
                <div class="card-body">
                    <a href="{{ route('panel.orders.index') }}" class="mb-1"><i class="fa fa-angle-left"></i> back</a>
                    <h2 class="mt-xxs">Viewing order #{{$order->id}}</h2>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Listing</th>
                                <td>{{$order->product_title}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status</th>
                                <td><span class="badge badge-warning">{{$order->status}}</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Amount</th>
                                <td>{{$order->amount}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Price</th>
                                <td>{{$order->price}} {{$order->currency}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Service fee</th>
                                <td>{{number_format($order->service_fee,9)}} {{$order->currency}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Buyer</th>
                                <td>{{$order->user->name}} ({{$order->user->username}})
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Seller</th>
                                <td>{{$order->seller->name}} ({{$order->seller->username}})
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Date&nbsp;Placed</th>
                                <td>{{$order->created_at->toDayDateTimeString()}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

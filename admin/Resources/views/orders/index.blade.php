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


                    {!! Form::open(['url' => url()->current(), 'method' => 'GET']) !!}
                    <div class="input-group mb-3">
                        {{Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => "Search..."])}}
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Search</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th scope="col" class="border-0">#</th>
                                <th scope="col" class="border-0">Listing</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0">Seller</th>
                                <th scope="col" class="border-0">Buyer</th>
                                <th scope="col" class="border-0">Total Price</th>
                                <th scope="col" class="border-0">Commission</th>
                                <th scope="col" class="border-0">Purchase Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $item)
                            <tr>
                                <td><a href="{{route('panel.orders.show', $item)}}"
                                        title="{{ $item->id }}">{{ $item->id }}</a></td>   
                                <td>{{str_limit($item->product_title, 40)}}</td>
                                <td><span class="badge badge-{{$item->status == 'finalized' ? 'success' : 'warning' }}">{{$item->status}}</span></td>
                                <td>{{$item->seller->username}}</td>
                                <td>{{$item->user->username}}</td>
                                <td>{{number_format($item->price,5)}} {{$item->currency}}</td>
                                <td>{{number_format($item->service_fee,5)}} {{$item->currency}}</td>
                                <td>{{$item->created_at->toFormattedDateString()}}</td>
                                <td>
                                    <a href="{{route('panel.orders.show', $item)}}" class="text-muted float-right">Check</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {{ $orders->appends(app('request')->except('page'))->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>

@stop

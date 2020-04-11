@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Disputes</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Disputes</a></li>
                            <li class="breadcrumb-item"><a href="/panel/disputes" class="breadcrumb-link">Disputes</a>
                            </li>
                            <li class="breadcrumb-item"><a href="/panel/disputes/{{$dispute->id}}/edit"
                                    class="breadcrumb-link">Dispute #{{$dispute->id}}</a>
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
                <h5 class="card-header">Dispute</h5>

                <div class="card-body">
                    <a style="float:right" href="{{ route('panel.disputes.index')}}" class="mb-1"><i
                            class="fa fa-angle-left" aria-hidden="true"></i> back</a>
                    <small>Disputed Created by: <span
                            class="badge badge-pill badge-dark">{{$dispute->buyer->username}}</span></small><small
                        style="float:right;"><span
                            class="{{$dispute->resolved == 1 ? 'badge badge-success' : 'badge badge-pill badge-danger'}}">{{$dispute->resolved == 1 ? 'Resolved' : 'Not resolved'}}</span>Created
                        At: <span class="badge badge-primary">{{$dispute->created_at}}</span></small>
                    @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    {!! form_start($form) !!}
                    <div class="form-group">
                        <label for="reason" class="control-label">Buyer</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$dispute->buyer->username}}">
                        <label for="reason" class="control-label">Buyer Stats</label>
                        <div class="form-group">
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Orders</th>
                                        <td style="border: 0;">{{$dispute->buyer->normal_orders->count()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Disputes (%)</th>
                                        <td>{{$dispute->buyer->disputesBuyer->count()}} ( @if($dispute->buyer->disputesBuyer->count()){{number_format(($dispute->buyer->disputesBuyer->count()/$dispute->buyer->normal_orders->count())*100,2)}} @else 0 @endif % )</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Spendings</th>
                                        <td style="border: 0;">{{number_format($sale_total_buyer,2)}} {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Average</th>
                                        <td style="border: 0;">@if($sale_total_buyer>0){{number_format($sale_total_buyer/$dispute->buyer->normal_orders->count(),2)}} @else 0.00 @endif {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label">Vendor</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$dispute->seller->username}}">
                        <label for="reason" class="control-label">Vendor Stats</label>
                        <div class="form-group">
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Sales</th>
                                        <td style="border: 0;">{{$dispute->seller->orders->count()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Disputes (%)</th>
                                        <td>{{$dispute->seller->disputesSeller->count()}} ( @if($dispute->seller->disputesSeller->count()) {{number_format(($dispute->seller->disputesSeller->count()/$dispute->seller->orders->count())*100,2)}} @else 0.00 @endif % )</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Sales Worth</th>
                                        <td style="border: 0;">{{number_format($sale_total_seller,2)}} {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Average</th>
                                        <td style="border: 0;">@if ($sale_total_seller>0){{number_format($sale_total_seller/$dispute->seller->orders->count(),2)}} @else 0.00 @endif {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label">Order</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$dispute->order->product_title}}">
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label">In the escrow</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$dispute->resolved == 1 ? 'Released' : number_format($dispute->order->price + $dispute->order->shipping_fee,7) }}">
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label">Order Currency</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$dispute->order->currency}}">
                    </div>



                    <div class="form-group">
                        <label for="reason" class="control-label">Conversation</label>
                        <div style="overflow-y: scroll;height:400px;" class="chat-module-body">
                            @foreach($dispute->replies as $message)
                            <div class="media chat-item">
                                <div class="media-body">
                                    <div class="chat-item-title">

                                        <span
                                            style="@if($message->adminreply == 1) color:blue; @elseif($message->user->id == $dispute->buyer->id) color:red; @else color:green; @endif"
                                            class="chat-item-author">{{$message->user_id == null ? $message->moderator->username : $message->user->username}}</span>
                                        <span>{{$message->created_at->diffForHumans()}}</span>
                                    </div>
                                    <div class="chat-item-body">
                                        <p>{{$message->message}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <h5>Decision Dispute</h5>
                        @if($dispute->resolved == 1)
                        <label class="custom-control custom-radio custom-control-inline">
                            @if($dispute->winner == $dispute->seller->id)
                            <input type="radio" name="decision" value="vendor" disabled="" checked="checked"
                                class="custom-control-input"><span class="custom-control-label">Vendor <span
                                    class="badge badge-pill badge-success">{{$dispute->seller->username}}</span>
                                wins</span><br>
                            @else
                            <input type="radio" name="decision" value="vendor" disabled=""
                                class="custom-control-input"><span class="custom-control-label">Vendor Wins</span>
                            @endif
                        </label>
                        @if($dispute->winner == $dispute->buyer->id)
                        <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="decision" value="buyer" disabled="" checked="checked"
                                class="custom-control-input"><span class="custom-control-label">Buyer <span
                                    class="badge badge-pill badge-success">{{$dispute->buyer->username}}</span>
                                Wins</span>
                        </label>
                        @else
                        <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="decision" value="buyer" disabled=""
                                class="custom-control-input"><span class="custom-control-label">Buyer Wins</span>
                        </label>
                        @endif
                        @else
                        <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="decision" value="vendor" class="custom-control-input"><span
                                class="custom-control-label">Vendor Wins</span>
                        </label>
                        <label class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="decision" value="buyer" class="custom-control-input"><span
                                class="custom-control-label">Buyer Wins</span>
                        </label>
                        @endif
                    </div>

                    {!! form_end($form) !!}

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

\@extends('panel::layouts.master')

@section('content')
<div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content ">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Dashboard Administrator </h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="ecommerce-widget">

            <div class="row">
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Users Registered Today</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{$users_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Total Users Active</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$users_total}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Vendor Active</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$vendors_total}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Orders Today</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$orders_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Total Orders</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$total_orders}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Total Listings</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$total_listings}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Bitcoin Total Balance</h5>
                            <div class="metric-value d-inline-block">
				{{$btc_balance->result}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Litecoin Total Balance</h5>
                            <div class="metric-value d-inline-block">
                            {{$ltc_balance->result}}
				</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Monero Total Balance</h5>
                            <div class="metric-value d-inline-block">
                         	{{$xmr_balance->result['balance']}}   
			 </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Monero in Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($monero_escrow->total,4)}}XMR</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Monero last 24h orders</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($monero_escrow_lastday->total,4)}}XMR</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Monero released Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($monero_released_escrow_lastday->total,4)}}XMR</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Monero Revenue</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($monero_fee_received,4)}}XMR</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Bitcoin in Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($bitcoin_escrow->total,5)}}BTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Bitcoin last 24h orders</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($bitcoin_escrow_lastday->total,5)}}BTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Bitcoin released Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($bitcoin_released_escrow_lastday->total,5)}}BTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Bitcoin Revenue</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($bitcoin_fee_received,5)}}BTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Litecoin in Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($litecoin_escrow->total,5)}}LTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Litecoin last 24h orders</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($litecoin_escrow_lastday->total,5)}}LTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Litecoin released Escrow</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($litecoin_released_escrow_lastday->total,5)}}LTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="text-muted">Litecoin Revenue</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{number_format($litecoin_fee_received,5)}}LTC</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     


            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <h5 class="card-header">Recent Multisig 2/3 Transactions</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Order</th>
                                            <th class="border-0">Multisig Address</th>
                                            <th class="border-0">Amount</th>
                                            <th class="border-0">Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($multisigtransactions as $multisig)
                                    <tr>
                                        <td>{{$multisig->id}}</td>
                                        <td>#{{$multisig->order_id}}</td>
                                        <td>{{$multisig->multisig_address}}</td>
                                        <td>{{number_format($multisig->multisig_amount,8)}}BTC</td>
                                        <td>{{$multisig->created_at}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3">
                @if(auth()->user()->username == "Augustus")
                    <div class="card">
                        <h5 class="card-header">Error Logs</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">Error Code</th>
                                            <th class="border-0">Error Log</th>
                                            <th class="border-0">Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($errorlogs as $log)
                                    <tr>
                                        <td>{{$log->code}}</td>
                                        <td>{{$log->message}}</td>
                                        <td>{{$log->created_at}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card">
                        <h5 class="card-header">Buy Logs</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">Type</th>
                                            <th class="border-0">Username</th>
                                            <th class="border-0">Amount</th>
                                            <th class="border-0">Cashed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($buylogs as $buy)
                                    <tr>
                                        <td>{{$buy->type}}</td>
                                        <td>{{$buy->username}}</td>
                                        <td>{{$buy->amount}} {{$buy->currency}}</td>
                                        <td>{{$buy->paid == 1 ? 'Paid out' : 'Not paid out'}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <h5 class="card-header">Recent Deposits</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">TX ID</th>
                                            <th class="border-0">User</th>
                                            <th class="border-0">Address</th>
                                            <th class="border-0">Currency</th>
                                            <th class="border-0">Amount</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                    @foreach($transactions as $deposit)
                                    @if($deposit->type == "Deposit")
                                    <tr>
                                        <td>{{$deposit->id}}</td>
                                        <td>{{$deposit->tx_id}}</td>
                                        <td>{{$deposit->user->username}}</td>
                                        <td>{{$deposit->address}}</td>
                                        <td>{{$deposit->amount}}</td>
                                        <td>@if($deposit->currency == 1) BTC @elseif($deposit->currency == 2) LTC @else XMR @endif</td>
                                        <td>{{$deposit->status}}</td>
                                        <td>{{$deposit->created_at}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <h5 class="card-header">Recent Withdraws</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                 <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">TX ID</th>
                                            <th class="border-0">User</th>
                                            <th class="border-0">Address</th>
                                            <th class="border-0">Currency</th>
                                            <th class="border-0">Amount</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                    @foreach($transactions as $withdraw)
                                    @if($withdraw->type == "Withdraw")
                                    <tr>
                                        <td>{{$withdraw->id}}</td>
                                        <td>{{$withdraw->tx_id}}</td>
                                        <td>{{$withdraw->user->username}}</td>
                                        <td>{{$withdraw->address}}</td>
                                        <td>{{$withdraw->amount}}</td>
                                        <td>@if($withdraw->currency == 1) BTC @elseif($withdraw->currency == 2) LTC @else XMR @endif</td>
                                        <td>{{$withdraw->status}}</td>
                                        <td>{{$withdraw->created_at}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


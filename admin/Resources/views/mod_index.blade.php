@extends('panel::layouts.master')

@section('content')
<div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content ">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Dashboard Moderator </h2>
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
                            <h5 class="text-muted">Open Disputes last 24h</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{$open_disputed_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Disputes last 24h</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$closed_disputed_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Open Tickets last 24h</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$open_tickets_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Tickets last 24h</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$closed_tickets_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Open Reports last 24h</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$open_reports_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Reports last 24h</h5>
                            <div class="metric-value d-inline-block">
                            <h1 class="mb-1">{{$closed_reports_today}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Open Disputes</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Disputes</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Open Tickets</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Tickets</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Open Reports</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Closed Reports</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">0</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <h5 class="card-header">Disputes last 24h</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Last Response</th>
                                            <th class="border-0">Seller</th>
                                            <th class="border-0">Buyer</th>
                                            <th class="border-0">Resolved</th>
                                            <th class="border-0">Winner</th>
                                            <th class="border-0">Created At</th>
                                            <th class="border-0">Closed By</th>
                                            <th class="border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($disputes as $dispute)
                                    <tr>
                                    <td>{{$dispute->id}}</td>
                                    <td>{{$dispute->usernameResponse() == null ? 'Nobody' : $dispute->usernameResponse()}}</td>
                                    <td>{{$dispute->seller->username}}</td>
                                    <td>{{$dispute->buyer->username}}</td>
                                    <td><span class="{{$dispute->winner != null ? 'badge-dot badge-success' : 'badge-dot badge-danger'}} "></span>{{$dispute->winner != null ? $dispute->winnerDispute->username : 'None'}}</td>
                                    <td><span class="{{$dispute->resolved == 1 ? 'badge badge-success' : 'badge badge-pill badge-danger'}}">{{$dispute->resolved == 1 ? 'Resolved' : 'Not resolved'}}</span></td>
                                    <td>{{$dispute->created_at}}</td>
                                    <td>{{$dispute->lastMod != null ? $dispute->lastMod->username :  'Nobody'}}</td>
                                    <td>
                                    <a href="{{route('panel.disputes.edit', $dispute->id)}}"
                                        class="text-muted float-right">{{$dispute->resolved == 1 ? 'Solved' : 'Check'}} <i class="{{$dispute->resolved == 1 ? 'text-success fa fa-check' : 'fa fa-eye'}}"></i></a>
                                     </td>
                                    </tr>
                                    @endforeach
                                        <tr>
                                            <td colspan="9"><a href="/panel/disputes"
                                                    class="btn btn-outline-light float-right">See all disputes</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <h5 class="card-header">Tickets last 7 days</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Created At</th>
                                            <th class="border-0">Username</th>
                                            <th class="border-0">Category</th>
                                            <th class="border-0">Last Response</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Closed By</th>
                                            <th class="border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tickets as $ticket)
                                    <tr>
                                    <td>{{$ticket->id}}</td>
                                    <td>{{$ticket->created_at}}</td>
                                    <td>{{$ticket->user->username}}</td>
                                    <td>{{$ticket->category}}</td>
                                    <td>{{$ticket->usernameResponse() == null ? 'Nobody' : $ticket->usernameResponse()}}</td>
                                    <td><span class="{{$ticket->status == 'Closed' ? 'badge badge-success' : 'badge badge-danger'}}">{{$ticket->status}}</span></td>
                                    <td>{{$ticket->lastMod != null ? $ticket->lastMod->username :  'Nobody'}}</td>
                                    <td>
                                    @if($ticket->status != "Closed")
                                    <a href="{{route('panel.tickets.edit', $ticket->id)}}"
                                        class="text-muted float-right">Review <i class="fa fa-chevron-right"></i></a>
                                    @else
                                    <a href="{{route('panel.tickets.edit', $ticket->id)}}"
                                        class="text-muted float-right">Solved <i
                                            class="text-success fa fa-check"></i></a>
                                    @endif
                                </td>
                                    </tr>
                                    @endforeach

                                        <tr>
                                            <td colspan="9"><a href="/panel/tickets"
                                                    class="btn btn-outline-light float-right">See all tickets</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <h5 class="card-header">Reports last 7 days</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Reported User</th>
                                            <th class="border-0">User</th>
                                            <th class="border-0">Reason</th>
                                            <th class="border-0">Notes</th>
                                            <th class="border-0">Active</th>
                                            <th class="border-0">Created at</th>
                                            <th class="border-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                    <td>{{$report->id}}</td>
                                    <td>{{$report->reportedUser->username}}</td>
                                    <td>{{$report->user->username}}</td>
                                    <td>{{$report->reason}}</td>
                                    <td>{{$report->notes}}</td>
                                    <td>{{$report->moderatorUser == null ? 'Not picked' : $report->moderatorUser->username}}</td>
                                    <td>{{$report->created_at}}</td>
                                    <td>
                                    @if($report->active)
                                    <a href="{{route('panel.moderatelistings.edit', $report->id)}}"
                                        class="text-muted float-right">Review <i class="fa fa-chevron-right"></i></a>
                                    @else
                                    @if(auth()->user()->is_admin == 2)
                                    <a href="{{route('panel.moderatelistings.edit', $report->id)}}"
                                        class="text-muted float-right">Solved <i
                                            class="text-success fa fa-check"></i></a>
                                    @else
                                    <span>Solved <i
                                            class="text-success fa fa-check"></i></span>

                                    @endif
                                    @endif
                                </td>
                                    </tr>
                                    @endforeach
                                        <tr>
                                            <td colspan="9"><a href="/panel/moderatelistings"
                                                    class="btn btn-outline-light float-right">See al reports</a></td>
                                        </tr>
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

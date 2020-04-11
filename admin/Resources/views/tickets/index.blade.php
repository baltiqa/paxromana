@extends('panel::layouts.master')


@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Tickets</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/tickets"
                                    class="breadcrumb-link">Tickets</a>
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
                <h5 class="card-header">Tickets</h5>
                <div class="card-body">
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-sm  ">
                        <thead class="thead- border-0">
                            <tr>
                                <th>#</th>
                                <th>Date Created</th>
                                <th>Latest Response By</th>
                                <th>Subject</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Closed By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td>{{$ticket->id}}</td>
                                <td>{{$ticket->created_at}}</td>
                                <td>{{$ticket->usernameResponse() == null ? 'Nobody' : $ticket->usernameResponse()}}</td>
                                <td>{!! str_limit($ticket->title,10) !!}</td>
                                <td>{{$ticket->user->username}}</td>
                                <td>{{$ticket->category}}</td>
                                <td>{!! str_limit($ticket->text,8) !!}</td>
                                <td><span class="{{$ticket->status == 'Closed' ? 'badge badge-success' : 'badge badge-danger'}}">{{$ticket->status}}</span></td>
                                <td>{{$ticket->lastMod != null ? $ticket->lastMod->username :  'Nobody'}}</td>
                                <td>
                                    @if($ticket->status != "Closed")
                                    <a href="{{route('panel.tickets.edit', $ticket->id)}}"
                                        class="badge badge-danger float-right">Review</a>
                                    @else
                                    <a href="{{route('panel.tickets.edit', $ticket->id)}}"
                                        class="badge badge-pill badge-success float-right">Solved</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $tickets->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>



@stop

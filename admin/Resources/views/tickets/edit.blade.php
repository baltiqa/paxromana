@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Ticket</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/tickets" class="breadcrumb-link">Ticket</a>
                            </li>
                            <li class="breadcrumb-item"><a href="/panel/tickets/{{$ticket->id}}/edit"
                                    class="breadcrumb-link">Ticket #{{$ticket->id}}</a>
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
                <h5 class="card-header">Ticket <span style="float:right;" class="badge badge-info"><a style="color:white;" href="/profile/{{$ticket->user->username}}" target="_blank">{{$ticket->user->username}} Profile ?</a></span></h5>

                <div class="card-body">
                            @if($ticket->status != "Closed")
                            <a href="{{route('panel.ticket.close',$ticket->id)}}" class="btn btn-primary" type="submit">Close Ticket</a>
                            @endif
                    <small>Ticket Created by: <span
                            class="badge badge-pill badge-info"><a style="color:white;" href="{{route('panel.users.edit', $ticket->user->username)}}" target="_blank">[Edit]{{$ticket->user->username}}</a></span></small>
                    <small style="float:right;"><span
                            class="{{$ticket->status == 'Closed' ? 'badge badge-success' : 'badge badge-danger'}}">{{$ticket->status}}</span>Created
                        At: <span class="badge badge-primary">{{$ticket->created_at}}</span></small>
                        @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    {!! form_start($form) !!}
                    {!! form_until($form, 'text') !!}

                    @if($ticket->category == 'Free Vendor Request')
                    <label for="username" class="control-label">PGP Key of {{$ticket->user->username}}</label>
                    <textarea class="form-control" disabled="disabled" name="text" cols="50" rows="10" id="text">
{{$ticket->user->pgp_key}}
                    </textarea>
                    @endif

                    <div class="form-group">
                        <label for="conversation" class="control-label">Conversation</label><br>
                          @if(count($ticket->replies)>0)
                          <div style="overflow-y: scroll;height:400px;" class="chat-module-body">
                            @foreach($ticket->replies as $message)
                            <div class="media chat-item">
                                <div class="media-body">
                                    <div class="chat-item-title">
                                        <span
                                            style="@if($message->user_id == 0) color:blue; @elseif($message->user->id == $ticket->user->id) color:red; @else color:green; @endif"
                                            class="chat-item-author">{{$message->user_id == null ? $message->moderator->username : $message->user->username}}</span>
                                        <span>{{$message->created_at->diffForHumans()}}</span>
                                    </div>
                                    <div class="chat-item-body">
                                        <p>{!! nl2br(e($message->text)) !!}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @endforeach
                            </div>
                        @else
                        The conversation is currently empty.
                        @endif
                    </div>
                    {!! form_rest($form) !!}
                    {!! form_end($form) !!}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

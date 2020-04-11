@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Reports</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/moderatelistings"
                                    class="breadcrumb-link">Reports</a>
                            </li>
                            <li class="breadcrumb-item"><a href="/panel/moderatelistings/{{$report->id}}/edit"
                                    class="breadcrumb-link">Report #{{$report->id}}</a>
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
                <h5 class="card-header">Reports</h5>

                <div class="card-body">
                    <a style="float:right" href="{{ route('panel.moderatelistings.index')}}" class="mb-1"><i
                            class="fa fa-angle-left" aria-hidden="true"></i> back</a>
                    <small>Reported by: <span
                            class="badge badge-pill badge-dark">{{$report->user->username}}</span> @if($report->listing)<a
                            href="{{$report->listing->url}}" target="_blank">(view)</a>@endif</small>
                    {!! form_start($form) !!}
                    <div class="form-group">
                        <label for="reason" class="control-label">Reported User</label>
                        <input class="form-control" disabled="disabled" type="text"
                            value="{{$report->reportedUser->username}}">
                    </div>
                    @if($report->active)
                    @if($report->reported_conversation != null)
                    <div class="form-group">
                        <label for="reason" class="control-label">Conversation</label>
                        <div style="overflow-y: scroll;height:200px;" class="chat-module-body">
                           @foreach($messages as $message)
                            <div class="media chat-item">
                                <div class="media-body">
                                    <div class="chat-item-title">
                                        <span style="{{$message->sender->username == $report->reportedUser->username ? 'color:red;' : 'color:green;'}}" class="chat-item-author">{{$message->sender->username}}</span>
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
                    @endif
                    @else
                    <span>This has been Solved, conversation is now destroyed <i class="text-success fa fa-check"></i></span>
                    @endif

                    {!! form_end($form) !!}

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

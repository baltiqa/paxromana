@extends('panel::layouts.master')

@section('content')
<div style="padding-top:0px;" class="dashboard-main-wrapper">
    <div class="main-container">
        <div class="navbar bg-white breadcrumb-bar border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Chat</li>
                </ol>
            </nav>
        </div>
        <div class="content-container">
            <div class="chat-module">
            <div class="input-group-append">
                                <button type="button" class="btn btn-danger">Empty Chat</button> <p style="margin: 20px; position: absolute; right: 0;">The staff chat will be soon online.</p>
            </div><br><br>
                <div class="chat-module-top">
                    <form>
                        <div class="input-group input-group-round">
                            <input type="search" class="form-control filter-list-input" placeholder="Search chat"
                                aria-label="Search Chat">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="chat-module-body">
                        <div class="media chat-item">
                            <img alt="Augustus" src="/web/images/noavatar.png" class="rounded-circle user-avatar-lg">
                            <div class="media-body">
                                <div class="chat-item-title">
                                    <span class="chat-item-author">Augustus <span
                                            class="badge badge-pill badge-danger">Emperor</span></span>
                                    <span><b>Juni 323 v.Chr</b></span>
                                </div>
                                <div class="chat-item-body">
                                    <p>Well, here is it. It is not fully operational!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-module-bottom">
                    <form class="chat-form">
                        <div class="input-group-append">
                        <textarea class="form-control" placeholder="Type message" rows="1"></textarea>
                                <button type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

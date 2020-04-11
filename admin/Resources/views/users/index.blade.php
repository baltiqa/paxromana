@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Users</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/users" class="breadcrumb-link">Users</a>
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
                <h5 class="card-header">Change Users</h5>
                <div class="card-body">
                    {!! Form::open(['url' => url()->current(), 'method' => 'GET']) !!}
                    <div class="input-group mb-3">
                        {{Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => "Search..."])}}
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Search</button>
                        </div>
                    </div>
                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Date registered</th>
                                <th scope="col">Last login</th>
                                <th scope="col">Rank</th>
                                <th scope="col">Roman Rate</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $item)
                            @if($item->is_admin == 1)
                            @else
                            <tr>
                                <td>{{$item->username}} {!! ($item->banned_at)?'<i class="text-muted">(banned)</i>':''
                                    !!}{!! ($item->withdraw_disabled)?'<i class="text-muted">(withdraw disabled)</i>':''
                                    !!}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->last_login_at->diffForHumans()}}</td>
                                <td>{{$item->is_headmod == 1 ? 'H-Mod' : $item->is_mod == 1 ? 'Mod'  : '' }}({{$item->trader_type == "individual" ? 'Vendor' : 'Buyer'}})</td>
                                <td>{{$item->trader_type == "individual" ? $item->avg_rating() : 'N/A'}}</td>
                                <td>
                                    <a href="{{route('panel.users.edit', $item->username)}}"
                                        class="text-muted float-right">Edit</a>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->appends(app('request')->except('page'))->links('panel::pagination.default') }}
                </div>
            </div>
        </div>
    </div>
</div>





@stop

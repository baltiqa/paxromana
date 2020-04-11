@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Listings</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/listings" class="breadcrumb-link">Listings</a>
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
                <h5 class="card-header">Listings</h5>
                <div class="card-body">
                    @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif

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
                                <th scope="col" class="w- border-0"></th>
                                <th scope="col" class="w-50 border-0">Title</th>
                                <th scope="col" class="w-25 border-0">User</th>
                                <th scope="col" class="w-25 border-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listings as $item)
                            <tr>
                                <th scope="row">{{$item->id}}</th>
                                <td><a
                                        href="{{route('panel.listings.edit', $item)}}">{{str_limit($item->title, 40)}} @if ($item->deleted_at != null) (deleted) @endif</a>
                                    @if($item->is_draft)<small class="badge badge-secondary">draft</small>@endif
                                    <br />@if($item->expires_at)<small class="text-muted">Expires
                                        {{$item->expires_at}}</small>@endif @if($item->ends_at)<small
                                        class="text-muted">Ends {{$item->ends_at}}</small>@endif</td>
                                <td>{{@$item->user->username}}</td>
                                <td>
                                    <a href="{{route('panel.listings.edit', $item)}}" class="text-muted float-right">Edit</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>


                    {{ $listings->appends(app('request')->except('page'))->links('panel::pagination.default') }}


                </div>
            </div>
        </div>
    </div>
</div>



@stop

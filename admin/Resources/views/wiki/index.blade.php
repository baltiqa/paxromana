@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Wiki News</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/wiki" class="breadcrumb-link">Wiki NEws</a>
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
                <h5 class="card-header">Wiki News</h5>
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
                    <a style="float:right;" href="{{ route('panel.wiki.create') }}" class="mb-1"><i class="fas fa-arrow-alt-circle-right"></i> Create Wiki News</a>
                        <thead class="thead- border-0">
                            <tr>
                                <th scope="col" class="w-5 border-0">Date Created</th>
                                <th scope="col">Title</th>
                                <th scope="col" class="w-25 border-0">Parent ID</th>
                                <th scope="col">Thumbs Up</th>
                                <th scope="col">Thumbs Down</th>
                                <th scope="col" class="w-25 border-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $item)
                            <tr>
                                 <td>{{$item->created_at}}</td>
                                <td>{{str_limit($item->title, 40)}}</td>
                                <td>{{$item->parent_id}}</td>
                                <td>{{$item->vote_up}}</td>
                                <td>{{$item->vote_down}}</td>
                                <td>
                                    <a href="{{route('panel.wiki.destroy', $item->id)}}" class="text-muted float-right ml-2">Remove</a>
                                    <a href="{{route('panel.wiki.edit', $item->id)}}" class="text-muted float-right">Edit</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {{ $pages->appends(app('request')->except('page'))->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>


@stop

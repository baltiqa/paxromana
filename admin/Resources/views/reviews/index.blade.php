@extends('panel::layouts.master')

@section('content')
<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Reviews</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/reviews" class="breadcrumb-link">Reviews</a>
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
                <h5 class="card-header">Reviews</h5>
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
                                <th>#</th>
                                <th>Commenter</th>
                                <th>Comment</th>
                                <th>Rate</th>
                                <th>Order ID</th>
                                <th>Seller</th>
                                <th>Created On</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($comments as $comment )
                            <tr>
                                <th scope="row">{{$comment->id}}</th>
                                <td>{{$comment->commenter->username}}</td>
                                <td>{{$comment->comment}}</td>
                                <td>{{number_format($comment->rate,2)}}</td>
                                <td>{{$comment->order_id}}</td>
                                <td>{{$comment->seller->username}}</td>

                                <td>{{$comment->created_at}}</td>
                                <td><a href="{{ route('panel.reviews.edit', $comment->id) }}" class="text-muted float-right">Edit</a></td>
                                <td>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {{ $comments->appends(app('request')->except('page'))->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>

@stop

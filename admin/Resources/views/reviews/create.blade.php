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
                            <li class="breadcrumb-item"><a href="/panel/reviews/{{$comment->id}}/edit" class="breadcrumb-link">Edit views</a>
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
                <h5 class="card-header">Edit Review</h5>
                <div class="card-body">
                    <a href="{{route('panel.reviews.index')}}" class="mb-1"><i class="fa fa-angle-left"
                            aria-hidden="true"></i> back</a>


                    {!! form_start($form) !!}
                    <div class="form-group">
                        <label  class="control-label">Commenter</label>
                        <input class="form-control" disabled="disabled" type="text" value="{{$comment->commenter->username}}">
                    </div>

                    {!! form_rest($form) !!}

                    {!! form_end($form) !!}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

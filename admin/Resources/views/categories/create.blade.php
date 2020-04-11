@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Categories</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/listings" class="breadcrumb-link">Categories</a>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">@if(!$form->getModel()) New Category @else Edit Category @endif</a>
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
                <h5 class="card-header">@if(!$form->getModel()) New Category @else Edit Category @endif</h5>
                <div class="card-body">
                <a href="{{ route('panel.categories.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>
                {!! form_start($form)  !!}
                  {!! form_until($form, 'order')  !!}
                  <label for="Parent category" class="control-label">Parent category</label>
                  <div class="form-group">
                      {!! $dropdown  !!}
                  </div>
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

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
                            <li class="breadcrumb-item"><a href="/panel/listing/{{ $listing->hash }}/edit" class="breadcrumb-link">Edit Listing</a>
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
					{!! form_start($form)  !!}
					{!! form_until($form, 'title')  !!}
					<label for="Category" class="control-label">Category</label>
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

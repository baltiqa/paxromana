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
                            <li class="breadcrumb-item"><a href="/panel/wiki" class="breadcrumb-link">Wiki News</a>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">@if(!$form->getModel()) New Wiki News @else Edit Wiki News @endif</a>
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
                <h5 class="card-header">@if(!$form->getModel()) New Wiki News @else Edit Wiki News @endif</h5>
                <div class="card-body">
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    
                <a href="{{ route('panel.wiki.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>
                
                {!! form_start($form)  !!}
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

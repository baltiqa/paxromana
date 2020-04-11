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
                            <li class="breadcrumb-item"><a href="/panel/categories"
                                    class="breadcrumb-link">Categories</a>
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
                <h5 class="card-header">Categories</h5>
                <div class="card-body">
                    @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <h2>Categories <a href="/panel/categories/create" class="btn btn-link btn-xs"><i
                                class="mdi mdi-plus"></i> Add new</a></h2>

                    <table class="table table-sm table-striped table-hover">

                        <thead>
                            <tr>
                                <th class="w-75">Name</th>
                                <th class="w-25"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($categories as $category )
                            <tr>
                                <td>{!! str_repeat("&mdash;", $category['depth']) !!} {{$category['name']}}</td>
                                <td>
                                    <a href="{{ route('panel.categories.destroy', $category['id']) }}" class="text-muted float-right ml-2"><i
                                            class="fa fa-times"></i></a>
                                    <a href="/panel/categories/<?= $category['id'] ?>/edit"
                                        class=" text-muted float-right"><i class="fas fa-pencil-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
</div>


@stop

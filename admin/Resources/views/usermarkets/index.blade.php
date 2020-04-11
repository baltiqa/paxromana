@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">User Feedbacks from other markets</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/usermarkets" class="breadcrumb-link">Users</a>
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
                                <th scope="col">EmpireMarket</th>
                                <th scope="col">BerlusconiMarket</th>
                                <th scope="col">Silk Road</th>
                                <th scope="col">SamsaraMarket</th>
                                <th scope="col">ApollonMarket</th>
                                <th scope="col">DreamMarket</th>
                                <th scope="col">TochkaMarket</th>
                                <th scope="col">CryptoniaMarket</th>
                                <th scope="col">NightmareMarket</th>
                                <th scope="col">WallstreetMarket</th>
                                <th scope="col">DarkMarket</th>
                                <th scope="col">GreyMarket</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $item)
                            <tr>
                                <td>{{$item->username}}</td>
                                <td>{{$item->marketName('Empire')}}</td>
                                <td>{{$item->marketName('Berlus')}}</td>
                                <td>{{$item->marketName('Samsara')}}</td>
                                <td>{{$item->marketName('Silk')}}</td>
                                <td>{{$item->marketName('Apollon')}}</td>
                                <td>{{$item->marketName('Dream')}}</td>
                                <td>{{$item->marketName('Tochka')}}</td>
                                <td>{{$item->marketName('Cryptonia')}}</td>
                                <td>{{$item->marketName('Nightmare')}}</td>
                                <td>{{$item->marketName('Wallstreet')}}</td>
                                <td>{{$item->marketName('Dark')}}</td>
                                <td>{{$item->marketName('Grey')}}</td>
                                <td>
                                    <a href="{{route('panel.usermarkets.edit', $item->username)}}"
                                        class="text-muted float-right">Edit</a>
                                </td>
                            </tr>
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

@extends('panel::layouts.master')


@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Disputes</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/disputes"
                                    class="breadcrumb-link">Disputes</a>
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
                <h5 class="card-header">Disputes</h5>
                <div class="card-body">
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-sm  ">
                        <thead class="thead- border-0">
                            <tr>
                                <th>#</th>
                                <th>Dispute Created On</th>
                                <th>Order</th>
                                <th>Seller</th>
                                <th>Buyer</th>
                                <th>Winner</th>
                                <th>Solved</th>
                                <th>Closed By</th>
                                <th>Last Response By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disputes as $dispute)
                            <tr>
                                <td>{{$dispute->id}}</td>
                                <td>{{$dispute->created_at}}</td>
                                <td>{{$dispute->order_id}}</td>
                                <td>{{$dispute->seller->username}}</td>
                                <td>{{$dispute->buyer->username}}</td>
                                <td><span class="{{$dispute->winner != null ? 'badge-dot badge-success' : 'badge-dot badge-danger'}} "></span>{{$dispute->winner != null ? $dispute->winnerDispute->username : 'None'}}</td>
                                <td><span class="{{$dispute->resolved == 1 ? 'badge badge-success' : 'badge badge-pill badge-danger'}}">{{$dispute->resolved == 1 ? 'Resolved' : 'Not resolved'}}</span></td>
                                <td>{{$dispute->lastMod != null ? $dispute->lastMod->username :  'Nobody'}}</td>
                                <td>{{$dispute->usernameResponse() == null ? 'Nobody' : $dispute->usernameResponse()}}</td>
                                <td>
                                    <a href="{{route('panel.disputes.edit', $dispute->id)}}"
                                        class="float-right {{$dispute->resolved == 1 ? 'badge badge-success' : 'badge badge-pill badge-danger'}}">{{$dispute->resolved == 1 ? 'Solved' : 'Check >'}} <i class="{{$dispute->resolved == 1 ? 'text-success' : ''}}"></i></a>

                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $disputes->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>



@stop

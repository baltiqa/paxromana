@extends('panel::layouts.master')


@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Reports</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/moderatelistings"
                                    class="breadcrumb-link">Reports</a>
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
                <h5 class="card-header">Reports</h5>
                <div class="card-body">
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-sm  ">
                        <thead class="thead- border-0">
                            <tr>
                                <th>Reason</th>
                                <th>Notes</th>
                                <th>Reported by</th>
                                <th>Reported User</th>
                                <th>Checked By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listings as $i => $listing)
                            <tr>
                                <td>{{$listing->reason}}</td>
                                <td>{{$listing->notes}}</td>
                                <td>{{$listing->user->username}}</td>
                                <td>{{$listing->reportedUser->username}}</td>
                                <td>{{$listing->moderatorUser == null ? 'Not picked' : $listing->moderatorUser->username}}</td>
                                <td>
                                    @if($listing->active)
                                    <a  href="{{route('panel.moderatelistings.edit', $listing->id)}}"
                                        class="badge badge-danger float-right">Review -></a>
                                    @else
                                    <a href="{{route('panel.moderatelistings.edit', $listing->id)}}"
                                        class="badge badge-pill badge-success float-right">Solved</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $listings->links('panel::pagination.default') }}

                </div>
            </div>
        </div>
    </div>
</div>



@stop

@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Users</h2>
                <div class="page-breadcrumb">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/users" class="breadcrumb-link">Users</a>
                            <li class="breadcrumb-item"><a href="/panel/user/{{$user->id}}/edit"
                                    class="breadcrumb-link">Edit {{$user->username}}</a>
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
                <h5 class="card-header">Edit User                  <span style="float:right;" class="badge badge-info"><a style="color:white;" href="/profile/{{$user->username}}" target="_blank">{{$user->username}} Profile ?</a></span></h5> 
                <div class="card-body">
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                @endif
                <span style="float:right;" class="badge badge-primary">Last Activity: {{$user->last_login_at->diffForHumans()}}</span>

                    {!! form_start($form) !!}
                    {!! form_until($form,'username') !!}
                    <div class="form-group">
                    <label for="display_name" class="control-label">Display name</label>
                   <input class="form-control" placeholder="This display Name will be showed in the profile of the user instead of the username" name="display_name" type="text" value="{{$user->display_name}}" id="display_name">
                     </div>
                     <div class="form-group">
                    <label for="display_name" class="control-label">Registered At</label>
                     <input class="form-control" name="created_at" type="text" value="{{$user->created_at}}" id="created_at">
                     </div>

                     <div class="form-group"> 
                     <label for="pgp_key" class="control-label">PGP Key</label>                
    <textarea class="form-control" rows="8" readonly="readonly" style="width: 100%; cursor: default">
{{$user->pgp_key}}
</textarea>
                     </div>

                    <hr>

                    @if($user->is_headmod == 1 || $user->is_mod == 1)
                    <div class="form-group">
                        <label for="rank" class="control-label">Rank</label>
                        <input class="form-control" type="text" disabled="disabled" value="@if($user->is_mod == 1 and $user->is_headmod == 1)Head Moderator @elseif($user->is_mod == 1) Moderator @endif">
                    </div>
                    <hr>
                    @endif


                    <div class="form-group">
                        <label for="rank" class="control-label">Trader Type</label>
                        <input class="form-control" type="text" disabled="disabled" value="{{$user->trader_type == 'individual' ? 'Vendor' : 'Buyer'}}">
                    </div>

                   
                    <hr>
                    @if(auth()->user()->is_headmod == 1 or auth()->user()->is_admin == 1 )
                    <h3>Head Moderator Options</h3>

                    <div class="form-group form-check-inline">
                        <input class="form-check-input" id="mod_enable" name="mod_enable" type="checkbox" value="1"  @if($user->is_mod == 1) checked @endif>
                        <label class="form-check-label" for="mod_enable">Moderator</label>
                    </div></br>

                    @if($user->is_mod == 1)
                    <div class="form-group form-check-inline">
                        <input class="form-check-input" id="dispute_allowed" name="dispute_allowed" type="checkbox" value="yes" @if($user->permiss()[0]['dispute'] != null) @if($user->permiss()[0]['dispute'] == "yes") checked @endif @endif>
                        <label class="form-check-label" for="dispute_allowed">Dispute Allowed</label>
                    </div>
                    <div class="form-group form-check-inline">
                        <input class="form-check-input" id="reports_allowed" name="reports_allowed" type="checkbox" value="yes" @if($user->permiss()[0]['report'] != null) @if($user->permiss()[0]['report'] == "yes") checked @endif @endif>
                        <label class="form-check-label" for="reports_allowed">Reports Allowed</label>
                    </div>                  
                    @endif
                   

                    <div class="form-group">
                    <label for="commission" class="control-label">Transaction fee</label>
                     <input class="form-control" name="commission" type="text" value="{{$user->commission}}" id="commission">
                     </div>
                     <br>
                  

                     <h3>Moderator Options</h3>
                    @if($user->trader_type == 'individual')
                    <div class="form-group form-check-inline">
                     @if($user->has_fe == 1)
                    <input class="form-check-input" id="fe_enabled" name="fe_enabled" type="checkbox" value="disable">
                    <label for="fe_enabled" class="control-label">Disable FE Rights</label>      
                     @else
                     <input class="form-check-input" id="fe_enabled" name="fe_enabled" type="checkbox" value="enable">
                    <label for="fe_enabled" class="control-label">Enable FE Rights</label>    
                     @endif
                     </div>
                    <hr>
                    @endif
                @endif



                    @if($user->trader_type == 'buyer')
                    <label class="control-label">Buyer Stats</label>
                    <div class="form-group">
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Orders</th>
                                        <td style="border: 0;">{{$user->normal_orders->count()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Disputes (%)</th>
                                        <td>{{$user->disputesBuyer->count()}} (@if($user->disputesBuyer->count()){{number_format(($user->disputesBuyer->count()/$user->normal_orders->count())*100,2)}}% @else 0.00% @endif)</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Spendings</th>
                                        <td style="border: 0;">{{number_format($sale_total_buyer,2)}} {{strtoupper(auth()->user()->currency)}} </td>
                                    </tr>
                                    <tr>
                                        <th>Average</th>
                                        <td style="border: 0;">@if($sale_total_buyer > 1){{number_format($sale_total_buyer/$user->normal_orders->count(),2)}} @else 0.00 @endif {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    @else
                    <label class="control-label">Vendor Stats</label>
                    <div class="form-group">
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Total Sales</th>
                                        <td style="border: 0;">{{$user->orders->count()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Disputes (%)</th>
                                        <td>{{$user->disputesSeller->count()}} (@if($user->disputesSeller->count()){{number_format(($user->disputesSeller->count()/$user->orders->count())*100,2)}}% @else 0% @endif)</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table" style="margin: 0;width: 50%;float: left;">
                                <tbody>
                                    <tr>
                                        <th style="border: 0;">Sales Worth</th>
                                        <td style="border: 0;">{{number_format($sale_total_seller,2)}} {{strtoupper(auth()->user()->currency)}} </td>
                                    </tr>
                                    <tr>
                                        <th>Average</th>
                                        <td style="border: 0;">@if($sale_total_seller > 1){{number_format($sale_total_seller/$user->orders->count(),2)}} @else 0.00 @endif {{strtoupper(auth()->user()->currency)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="{{route('panel.usermarkets.edit', $user->username)}}" class="btn btn-primary">Import Feedbacks</a><br><br>

                



                        
                    @endif

                    @if($user->trader_type == 'buyer')
                    <div class="form-group form-check-inline">
                    <input class="form-check-input" id="vendor_enabled" name="vendor_enabled" type="checkbox" value="yes">
                    <label for="vendor_enabled" class="control-label">Enable Vendor</label>    
                    </div>
                    @else
                    <div class="form-group form-check-inline">
                    <input class="form-check-input" id="vendor_enabled" name="vendor_enabled" type="checkbox" value="no">
                    <label for="vendor_enabled" class="control-label">Disable Vendor</label>    
                    </div>
                    
                    @endif

                    <hr>
                    {!! form_rest($form) !!}
                    {!! form_end($form, false) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

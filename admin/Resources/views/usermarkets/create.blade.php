@extends('panel::layouts.master')

@section('content')

<div class="container-fluid  dashboard-content">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">User Feedback</h2>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/panel" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/panel/usermarkets" class="breadcrumb-link">Imported Feedbacks</a>
                            <li class="breadcrumb-item"><a href="/panel/usermarkets/{{$user->username}}/edit" class="breadcrumb-link">{{$user->username}}</a>
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
                <h5 class="card-header">Import User feedbacks <span style="float:right;" class="badge badge-info"><a style="color:white;" href="/profile/{{$user->username}}" target="_blank">{{$user->username}} Profile ?</a></span></h5>
                <div class="card-body">
                <span style="float:right;" class="badge badge-dark"><a style="color:white;" href="{{route('panel.users.edit', $user->username)}}" target="_blank">Edit {{$user->username}} ?</a></span>
                @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif


                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input class="form-control" disabled="disabled" name="username" type="text" value="{{$user->username}}">
                    </div>
                  
                    {!! form_start($form) !!}
                    <table class="table table-sm table-striped">
                        <thead class="thead- border-0">
                            <tr>
                                <th scope="col">Market</th>
                                <th scope="col">Sales</th>
                                <th scope="col">Rate</th>
				<th scope="col">Percentage</th>
                                <th scope="col">Positive Feedback</th>
                                <th scope="col">Neutral Feedback</th>
                                <th scope="col">Negative Feedback</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Empire Market" src="/web/images/eeim.png">Empire Market</td>
                                <td><input class="form-control" name="empire_sales" type="text" value="{{$user->getMarketName('Empire') == null ? '' : $user->getMarketName('Empire')->sales }}"></td>
                                <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="empire_positive" type="text" value="{{$user->getMarketName('Empire') == null ? '' : $user->getMarketName('Empire')->positive }}"></td>
                                <td><input class="form-control" name="empire_neutral" type="text" value="{{$user->getMarketName('Empire') == null ? '' : $user->getMarketName('Empire')->neutral }}"></td>
                                <td><input class="form-control" name="empire_negative" type="text" value="{{$user->getMarketName('Empire') == null ? '' : $user->getMarketName('Empire')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Empire') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Empire')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Berlusconi Market" src="/web/images/ber.png">Berlusconi Market</td>
                                <td><input class="form-control" name="berlus_sales" type="text" value="{{$user->getMarketName('Berlus') == null ? '' : $user->getMarketName('Berlus')->positive }}"></td>
                                 <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="berlus_positive" type="text" value="{{$user->getMarketName('Berlus') == null ? '' : $user->getMarketName('Berlus')->positive }}"></td>
                                <td><input class="form-control" name="berlus_neutral" type="text" value="{{$user->getMarketName('Berlus') == null ? '' : $user->getMarketName('Berlus')->neutral }}"></td>
                                <td><input class="form-control" name="berlus_negative" type="text" value="{{$user->getMarketName('Berlus') == null ? '' : $user->getMarketName('Berlus')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Berlus') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Berlus')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Silk Road" src="/web/images/si.png">Silk Road</td>
                                <td><input class="form-control" name="silk_sales" type="text" value="{{$user->getMarketName('Silk') == null ? '' : $user->getMarketName('Silk')->sales }}"></td>
                                 <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="silk_positive" type="text" value="{{$user->getMarketName('Silk') == null ? '' : $user->getMarketName('Silk')->positive }}"></td>
                                <td><input class="form-control" name="silk_neutral" type="text" value="{{$user->getMarketName('Silk') == null ? '' : $user->getMarketName('Silk')->neutral }}"></td>
                                <td><input class="form-control" name="silk_negative" type="text" value="{{$user->getMarketName('Silk') == null ? '' : $user->getMarketName('Silk')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Silk') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Silk')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="SamSara Market" src="/web/images/samsara.png">Samsara Market</td>
                                <td><input class="form-control" name="samsara_sales" type="text" value="{{$user->getMarketName('Samsara') == null ? '' : $user->getMarketName('Samsara')->sales }}"></td>
                                 <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="samsara_positive" type="text" value="{{$user->getMarketName('Samsara') == null ? '' : $user->getMarketName('Samsara')->positive }}"></td>
                                <td><input class="form-control" name="samsara_neutral" type="text" value="{{$user->getMarketName('Samsara') == null ? '' : $user->getMarketName('Samsara')->neutral }}"></td>
                                <td><input class="form-control" name="samsara_negative" type="text" value="{{$user->getMarketName('Samsara') == null ? '' : $user->getMarketName('Samsara')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Samsara') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Samsara')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Apollon Market" src="/web/images/apol.png">Apollon Market</td>
                                <td><input class="form-control" name="apollon_sales" type="text" value="{{$user->getMarketName('Apollon') == null ? '' : $user->getMarketName('Apollon')->sales }}"></td>
                                 <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="apollon_positive" type="text" value="{{$user->getMarketName('Apollon') == null ? '' : $user->getMarketName('Apollon')->positive }}"></td>
                                <td><input class="form-control" name="apollon_neutral" type="text" value="{{$user->getMarketName('Apollon') == null ? '' : $user->getMarketName('Apollon')->neutral }}"></td>
                                <td><input class="form-control" name="apollon_negative" type="text" value="{{$user->getMarketName('Apollon') == null ? '' : $user->getMarketName('Apollon')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Apollon') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Apollon')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>

                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Wallstreet Market" src="/web/images/walls.png">Wallstreet Market</td>
                                <td><input class="form-control" name="wall_sales" type="text" value="{{$user->getMarketName('Wallstreet') == null ? '' : $user->getMarketName('Wallstreet')->sales }}"></td>
                                 <td>N/A</td>
				<td>N/A</td>
                                <td><input class="form-control" name="wall_positive" type="text" value="{{$user->getMarketName('Wallstreet') == null ? '' : $user->getMarketName('Wallstreet')->positive }}"></td>
                                <td><input class="form-control" name="wall_neutral" type="text" value="{{$user->getMarketName('Wallstreet') == null ? '' : $user->getMarketName('Wallstreet')->neutral }}"></td>
                                <td><input class="form-control" name="wall_negative" type="text" value="{{$user->getMarketName('Wallstreet') == null ? '' : $user->getMarketName('Wallstreet')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Wallstreet') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Wallstreet')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Dream Market" src="/web/images/d.png">Dream Market</td>
                                <td><input class="form-control" name="dream_rate" type="text" value="{{$user->getMarketName('Dream') == null ? '' : $user->getMarketName('Dream')->rate }}"></td>
                                <td><input class="form-control" name="dream_sales" type="text" value="{{$user->getMarketName('Dream') == null ? '' : $user->getMarketName('Dream')->sales }}"></td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>N/A</td>
				<td>N/A</td>
                                <td>
                                @if($user->getMarketName('Dream') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Dream')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Cryptonia Market" src="/web/images/cd.png">Cryptonia Market</td>
                                <td><input class="form-control" name="crypt_sales" type="text" value="{{$user->getMarketName('Cryptonia') == null ? '' : $user->getMarketName('Cryptonia')->sales }}"></td>
                                <td>N/A</td>
				<td><input class="form-control" name="crypt_percentage" type="text" value="{{$user->getMarketName('Cryptonia') == null ? '' : $user->getMarketName('Cryptonia')->percentage }}"></td>
                                <td>N/A</td>
                         	<td>N/A</td>
				<td>N/A</td>
                                <td>
                                @if($user->getMarketName('Cryptonia') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Cryptonia')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Nightmare Market" src="/web/images/nih.png">Nightmare Market</td>
                                <td><input class="form-control" name="nightmare_sales" type="text" value="{{$user->getMarketName('Nightmare') == null ? '' : $user->getMarketName('Nightmare')->sales }}"></td>
                                <td>N/A</td>
<td>N/A</td>
                                <td><input class="form-control" name="nightmare_positive" type="text" value="{{$user->getMarketName('Nightmare') == null ? '' : $user->getMarketName('Nightmare')->positive }}"></td>
                                <td>N/A</td>
                                <td><input class="form-control" name="nightmare_negative" type="text" value="{{$user->getMarketName('Nightmare') == null ? '' : $user->getMarketName('Nightmare')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Nightmare') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Nightmare')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Grey Market" src="/web/images/grey.png">Grey Market</td>
                                <td><input class="form-control" name="grey_sales" type="text" value="{{$user->getMarketName('Grey') == null ? '' : $user->getMarketName('Grey')->sales }}"></td>
                                <td><input class="form-control" name="grey_rate" type="text" value="{{$user->getMarketName('Grey') == null ? '' : $user->getMarketName('Grey')->rate }}"></td>
				<td>N/A</td>
				<td>N/A</td>
				<td>N/A</td>
				<td>N/A</td>
                                <td>
                                @if($user->getMarketName('Grey') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Grey')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 32px;width: 32px;" title="Dark Market" src="/web/images/darkm.png">Dark Market</td>
                                <td><input class="form-control" name="dark_sales" type="text" value="{{$user->getMarketName('Dark') == null ? '' : $user->getMarketName('Dark')->sales }}"></td>
                                <td>N/A</td>
                                <td><input class="form-control" name="dark_positive" type="text" value="{{$user->getMarketName('Dark') == null ? '' : $user->getMarketName('Dark')->positive }}"></td>
                                <td><input class="form-control" name="dark_neutral" type="text" value="{{$user->getMarketName('Dark') == null ? '' : $user->getMarketName('Dark')->neutral }}"></td>
                                <td><input class="form-control" name="dark_negative" type="text" value="{{$user->getMarketName('Dark') == null ? '' : $user->getMarketName('Dark')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Dark') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Dark')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td><img style="height: 20px;width: 32px;" title="Tochka Market" src="/web/images/tochka.png">Tochka Market</td>
                                <td><input class="form-control" name="tochka_sales" type="text" value="{{$user->getMarketName('Tochka') == null ? '' : $user->getMarketName('Tochka')->sales }}"></td>
                                <td>N/A</td>
                                <td><input class="form-control" name="tochka_positive" type="text" value="{{$user->getMarketName('Tochka') == null ? '' : $user->getMarketName('Tochka')->positive }}"></td>
                                <td><input class="form-control" name="tochka_neutral" type="text" value="{{$user->getMarketName('Tochka') == null ? '' : $user->getMarketName('Tochka')->neutral }}"></td>
                                <td><input class="form-control" name="tochka_negative" type="text" value="{{$user->getMarketName('Tochka') == null ? '' : $user->getMarketName('Tochka')->negatives }}"></td>
                                <td>
                                @if($user->getMarketName('Tochka') != null)
                                  <a href="{{route('panel.market.destroy',$user->getMarketName('Tochka')->id)}}" class="btn btn-primary">Remove</a>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! form_end($form) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

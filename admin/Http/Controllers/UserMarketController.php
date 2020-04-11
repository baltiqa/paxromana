<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMarket;
use Kris\LaravelFormBuilder\FormBuilder;


class UserMarketController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = new User;
        if($request->get('q')) {
            $users = User::where('username', 'like', "%{$request->get('q')}%");           
        }

        $data['users'] = $users->where('trader_type','individual')->orderBy('created_at', 'DESC')->paginate(15);

        return view('panel::usermarkets.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, FormBuilder $formBuilder)
    {
        $user = User::where('username',$user)->first();
        if($user == null) {
            abort(404);
        }
        $form = $formBuilder->create('Modules\Panel\Forms\MarketForm', [
            'method' => 'PUT',
            'url' => route('panel.usermarkets.update', $user->username),
            'model' => $user
        ]);
        return view('panel::usermarkets.create', compact('form','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $user = User::where('username',$user)->first();
        if($user == null) {
            abort(404);
        }

        if($request->input('empire_positive')) {
            $market = UserMarket::where('market_title',"Empire")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Empire";
                $newmarket->sales = $request->input('empire_sales');
                $newmarket->positive = $request->input('empire_positive');
                $newmarket->neutral = $request->input('empire_neutral');
                $newmarket->negatives =$request->input('empire_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('empire_sales');
                $market->positive = $request->input('empire_positive');
                $market->neutral = $request->input('empire_neutral');
                $market->negatives =$request->input('empire_negative');
                $market->save();
            }

            
        }


        if($request->input('apollon_positive')) {
            $market = UserMarket::where('market_title',"Apollon")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Apollon";
                $newmarket->sales = $request->input('apollon_sales');
                $newmarket->positive = $request->input('apollon_positive');
                $newmarket->neutral = $request->input('apollon_neutral');
                $newmarket->negatives =$request->input('apollon_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
            $market->sales = $request->input('apollon_sales');
            $market->positive = $request->input('apollon_positive');
            $market->neutral = $request->input('apollon_neutral');
            $market->negatives =$request->input('apollon_negative');
            $market->save();
            }

            
        }

        if($request->input('berlus_positive')) {
            $market = UserMarket::where('market_title',"Berlus")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Berlus";
                $newmarket->sales = $request->input('berlus_sales');
                $newmarket->positive = $request->input('berlus_positive');
                $newmarket->neutral = $request->input('berlus_neutral');
                $newmarket->negatives =$request->input('berlus_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
            $market->sales = $request->input('berlus_sales');
            $market->positive = $request->input('berlus_positive');
            $market->neutral = $request->input('berlus_neutral');
            $market->negatives =$request->input('berlus_negative');
            $market->save();
            }

            
        }

        if($request->input('nightmare_positive')) {
            $market = UserMarket::where('market_title',"Nightmare")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Nightmare";
                $newmarket->sales = $request->input('nightmare_sales');
                $newmarket->positive = $request->input('nightmare_positive');
                $newmarket->negatives =$request->input('nightmare_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('nightmare_sales');
                $market->positive = $request->input('nightmare_positive');
                $market->neutral = $request->input('nightmare_neutral');
                $market->negatives =$request->input('nightmare_negative');
                $market->save();
            }

           
        }

        if($request->input('silk_positive')) {
            $market = UserMarket::where('market_title',"Silk")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Silk";
                $newmarket->sales = $request->input('silk_sales');
                $newmarket->positive = $request->input('silk_positive');
                $newmarket->neutral = $request->input('silk_neutral');
                $newmarket->negatives =$request->input('silk_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('silk_sales');
                $market->positive = $request->input('silk_positive');
                $market->neutral = $request->input('silk_neutral');
                $market->negatives =$request->input('silk_negative');
                $market->save();
            }

           
        }


        if($request->input('crypt_percentage')) {
            $market = UserMarket::where('market_title',"Cryptonia")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Cryptonia";
                $newmarket->sales = $request->input('crypt_sales');
                $newmarket->percentage = $request->input('crypt_percentage');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('crypt_sales');
                $market->percentage = $request->input('crypt_percentage');
                $market->save();
            }

           
        }

        if($request->input('dream_rate')) {
            $market = UserMarket::where('market_title',"Dream")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Dream";
                $newmarket->sales = $request->input('dream_sales');
                $newmarket->rate = $request->input('dream_rate');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('dream_sales');
                $market->rate = $request->input('dream_rate');
                $market->save();
            }
        }

        if($request->input('samsara_positive')) {
            $market = UserMarket::where('market_title',"Samsara")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Samsara";
                $newmarket->sales = $request->input('samsara_sales');
                $newmarket->positive = $request->input('samsara_positive');
                $newmarket->neutral = $request->input('samsara_neutral');
                $newmarket->negatives =$request->input('samsara_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('samsara_sales');
                $market->positive = $request->input('samsara_positive');
                $market->neutral = $request->input('samsara_neutral');
                $market->negatives =$request->input('samsara_negative');
                $market->save();
            }
        }

        if($request->input('grey_sales')) {
            $market = UserMarket::where('market_title',"Grey")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Grey";
  		$newmarket->sales = $request->input('grey_sales');
                $newmarket->rate = $request->input('grey_rate');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
		$market->sales = $request->input('grey_sales');
                $market->rate = $request->input('grey_rate');
                $market->save();
            }
        }

        if($request->input('dark_positive')) {
            $market = UserMarket::where('market_title',"Dark")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Dark";
                $newmarket->sales = $request->input('dark_sales');
                $newmarket->positive = $request->input('dark_positive');
                $newmarket->neutral = $request->input('dark_neutral');
                $newmarket->negatives =$request->input('dark_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('dark_sales');
                $market->positive = $request->input('dark_positive');
                $market->neutral = $request->input('dark_neutral');
                $market->negatives =$request->input('dark_negative');
                $market->save();
            }
        }

        if($request->input('wall_positive')) {
            $market = UserMarket::where('market_title',"Wallstreet")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Wallstreet";
                $newmarket->sales = $request->input('wall_sales');
                $newmarket->positive = $request->input('wall_positive');
                $newmarket->neutral = $request->input('wall_neutral');
                $newmarket->negatives =$request->input('wall_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('wall_sales');
                $market->positive = $request->input('wall_positive');
                $market->neutral = $request->input('wall_neutral');
                $market->negatives =$request->input('wall_negative');
                $market->save();
            }
        }

        if($request->input('tochka_positive')) {
            $market = UserMarket::where('market_title',"Tochka")->where('user_id',$user->id)->first();
            if($market == null) {
                $newmarket = new UserMarket;
                $newmarket->market_title = "Tochka";
                $newmarket->sales = $request->input('tochka_sales');
                $newmarket->positive = $request->input('tochka_positive');
                $newmarket->neutral = $request->input('tochka_neutral');
                $newmarket->negatives =$request->input('tochka_negative');
                $newmarket->user_id = $user->id;
                $newmarket->save();
            } else {
                $market->sales = $request->input('tochka_sales');
                $market->positive = $request->input('tochka_positive');
                $market->neutral = $request->input('tochka_neutral');
                $market->negatives =$request->input('tochka_negative');
                $market->save();
            }
        }



        return redirect()->back()->with('message','Successfull saved');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($market)
    {
        $market = UserMarket::where('id',$market)->first();
        if($market == null) {
            return redirect()->back()->with('message','Failed');
        }

       $market->delete();

        return redirect()->back()->with('message','Successfull saved');
    }
}

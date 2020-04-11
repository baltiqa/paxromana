<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Models\CurrencyRates;
use App\Http\Controllers\NotificationController;


class UsersController extends Controller
{

    protected $btc_rates;
    protected $ltc_rates;
    protected $xmr_rates;

    public function __construct()
    {
        $rates = new CurrencyRates();

        $this->btc_rates = $rates->where("currency_id",1)->first();
        $this->ltc_rates = $rates->where("currency_id",2)->first();
        $this->xmr_rates = $rates->where("currency_id",3)->first();
    }


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

        $data['users'] = $users->orderBy('created_at', 'DESC')->paginate(15);

        return view('panel::users.index', $data);
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
        if($user->is_admin == 1) {
            abort(404);
        }


        $form = $formBuilder->create('Modules\Panel\Forms\UserForm', [
            'method' => 'PUT',
            'url' => route('panel.users.update', $user->username),
            'model' => $user
        ]);

        $currency = auth()->user()->currency;
        $buyerxmr = $user->getMoneroTotalSpends();
        $buyerltc = $user->getLitecoinTotalSpends();
        $buyerbtc = $user->getBitcoinTotalSpends();

        $sale_total_buyer = ($buyerxmr * $this->xmr_rates->$currency) + ($buyerltc * $this->ltc_rates->$currency) + ($buyerbtc * $this->btc_rates->$currency);

        $sellerxmr = $user->getMoneroTotalSales();
        $sellerltc = $user->getLitecoinTotalSales();
        $sellerbtc = $user->getBitcoinTotalSales();

        $sale_total_seller = ($sellerxmr * $this->xmr_rates->$currency) + ($sellerltc * $this->ltc_rates->$currency) + ($sellerbtc * $this->btc_rates->$currency);

        return view('panel::users.create', compact('form','user','sale_total_buyer','sale_total_seller'));
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
        if($user->is_admin == 1) {
            abort(404);
        }

        $notifications = new NotificationController();

        

        if($user->is_headmod == 1 && $request->input('is_banned') != null) {
            return redirect()->back()->with('message','You can\'t ban the head moderator');
        }

        $user->fill($request->all());

        if($request->get('is_banned') != null) {
            $user->banned_at = Carbon::now();
            $user->reason_ban = $request->input('reason_ban');

            if($user->trader_type == "individual") {
                    foreach($user->listings as $listing) {
                        $listing->is_published = 0;
                        $listing->deleted_at = Carbon::now();
                        $listing->save();
                    }
            }
        } else {
            if($user->trader_type == "individual") {
                foreach($user->trashedListings as $listing) {
                    $listing->is_published = 1;
                    $listing->deleted_at =  null;
                    $listing->save();
                }
            }

            $user->banned_at = null;
            $user->reason_ban = null;
        }


     

        switch($request->input('vendor_enabled')) {
            case "no":
                $user->trader_type="buyer";
                $user->has_fe = 0;
                if(count($user->listings) >1) {
                    foreach($user->listings as $listing) {
                        $listing->is_published = 0;
                        $listing->deleted_at = Carbon::now();
                        $listing->save();
                    }
                }
            break;
            case "yes":
                $user->trader_type="individual";
                if(count($user->trashedListings) >1) {
                    foreach($user->trashedListings as $listing) {
                        $listing->is_published = 1;
                        $listing->deleted_at =  null;
                       $listing->save();
                    }
                }
                $notifications->welcomeAsVendor($user->id);


            break;
        }



        if(auth()->user()->is_headmod == 1 || auth()->user()->is_admin == 1) {
           

            $permissionDispute = $request->input('dispute_allowed') == "yes" ? "yes" : "no";
            $permissionReport =  $request->input('reports_allowed') == "yes" ? "yes" : "no";

            $permissions[] = [
                'report' => $permissionReport,
                'dispute' => $permissionDispute,
            ];

            $user->has_fe = $request->input('fe_enabled') == "enable" ? 1 : 0;

            if($request->input('fe_enabled') == "enable") {
                $notifications->FErightEnabled($user->id);
            }

            $user->commission = $request->input('commission');

            $user->is_mod = $request->input('mod_enable') == 1 ? 1 : 0;



            $user->permissions = serialize($permissions);

        }

       

        $user->display_name = $request->input('display_name');
        $user->created_at = $request->input('created_at');

      
        $user->withdraw_disabled =  $request->input('withdraw_disabled') == 1 ? 1 : 0;
        $user->save();

        

        return redirect()->back()->with('message','Successfull saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
	#dd($user);
        $user->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.users.index');
    }
}

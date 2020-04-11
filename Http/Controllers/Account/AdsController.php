<?php

namespace App\Http\Controllers\Account;

use App\Models\CurrencyRates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\BuyLog;
use App\Models\User;
use Carbon\Carbon;
use MetaTag;
use Validator;
class AdsController extends Controller
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
    public function index($listing)
    {
        if(auth()->user()->id != $listing->user_id) {
            abort(404);
        }

        

        MetaTag::set('title', __('messages.buyads_title'));
        MetaTag::set('id', "2");
        MetaTag::set('vendor_id', "2");

        $count = 5 - Listing::whereNotNull('spotlight')->whereDate('spotlight','>', Carbon::now())->get()->count();

        
        $data = [];
        $data['listing'] = $listing;
        $data['count'] = $count;

		return view('account.ads',$data);
    }

    public function buyingAds($listing,Request $request) {
        if(auth()->user()->id != $listing->user_id) {
            abort(404);
        }


        $count = 10 - Listing::whereNotNull('spotlight')->whereDate('spotlight','>', Carbon::now())->get()->count();
        if($request->input('stara3') != null && $count == 0) {
            return redirect()->back()->with('error',__('validation.already_soldout'));
        }
        if($request->input('stara3') != null && $listing->spotlight >Carbon::now()) {
            return redirect()->back()->with('error',__('validation.already_active'));
        }

        if($request->input('stara2') != null && $listing->priority_until >Carbon::now()) {
            return redirect()->back()->with('error',__('validation.already_active'));
        }

        if($request->input('stara1') != null && $listing->bold_until >Carbon::now()) {
            return redirect()->back()->with('error',__('validation.already_active'));
        }

        


        $currency = "usd";

        switch($request->input('stara1')) {
            case 'xmr':
                if($listing->parent_id != 0) {
                    return redirect()->back()->with('error',__('validation.not_allowed'));
                }
                $price = 5 / $this->xmr_rates->$currency;
                if(auth()->user()->xmrBalance($price)) {
                    $listing->bold_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+";
                    $buylog->currency = "XMR";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                } else {
                return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
            break;
            case 'btc':
                if($listing->parent_id != 0) {
                    return redirect()->back()->with('error',__('validation.not_allowed'));
                }

                $price = 5/ $this->btc_rates->$currency;
                if(auth()->user()->btcBalance($price)) {
                    $listing->bold_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+";
                    $buylog->currency = "BTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.btc_not_enough'));
                    }
            break;
            case 'ltc':
                if($listing->parent_id != 0) {
                    return redirect()->back()->with('error',__('validation.not_allowed'));
                }
                $price = 5/ $this->ltc_rates->$currency;
                if(auth()->user()->ltcBalance($price)) {
                    $listing->bold_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+";
                    $buylog->currency = "LTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                 }
            break; 
        }

        switch($request->input('stara2')) {
            case 'xmr':
                $price = 10/ $this->xmr_rates->$currency;
                if(auth()->user()->xmrBalance($price)) {
                    $listing->priority_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A++";
                    $buylog->currency = "XMR";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                } else {
                    return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
            break;
            case 'btc':
                $price = 10/ $this->btc_rates->$currency;
                if(auth()->user()->btcBalance($price)) {
                    $listing->priority_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A++";
                    $buylog->currency = "BTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.btc_not_enough'));
                    }
            break;
            case 'ltc':
                $price = 10/ $this->ltc_rates->$currency;
                if(auth()->user()->ltcBalance($price)) {
                    $listing->priority_until = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A++";
                    $buylog->currency = "LTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                 }
            break; 
        }

        switch($request->input('stara3')) {
            case 'xmr':
                $price = 60/ $this->xmr_rates->$currency;
                if(auth()->user()->xmrBalance(10)) {
                    $listing->spotlight = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+++";
                    $buylog->currency = "XMR";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                } else {
                    return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
            break;
            case 'btc':
                $price = 60/ $this->btc_rates->$currency;
                if(auth()->user()->btcBalance($price)) {
                    $listing->spotlight = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+++";
                    $buylog->currency = "BTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.btc_not_enough'));
                    }
            break;
            case 'ltc':
                $price = 60/ $this->ltc_rates->$currency;
                if(auth()->user()->ltcBalance($price)) {
                    $listing->spotlight = Carbon::now()->addDays(5);
                    $listing->save();
                    $buylog = new BuyLog;
                    $buylog->username = auth()->user()->username;
                    $buylog->amount = $price;
                    $buylog->type = "Ads Star A+++";
                    $buylog->currency = "LTC";
                    $buylog->paid = 0;
                    $buylog->save();
                    return redirect()->back()->with('message',__('validation.bought_ads'));
                    } else {
                        return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                 }
            break; 
        }
        
    }



}

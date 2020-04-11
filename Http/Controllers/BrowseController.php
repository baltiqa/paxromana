<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\CurrencyRates;
use App\Http\Controllers\Account\ProfileController;
use App\Models\Category;
use App\Models\News;
use GNUPG;
use Carbon\Carbon;
use MetaTag;
use App\Models\User;


class BrowseController extends Controller
{

    public function listings() {

         $listingsRandom = Listing::whereDate('spotlight','<', Carbon::now())->get()->random(25)->shuffle();
         $listingAds = Listing::whereDate('spotlight','>', Carbon::now())->get()->random(5)->shuffle();

            MetaTag::set('title',__('messages.homepage_title'));
    
            $categories = Category::GetCategoriesWithTotalListings();
    
            $news = News::Where("featured","1")->get();
    
            $rates = new CurrencyRates();
         
            $btc_rates = $rates->where("currency_id",1)->first();
            $ltc_rates = $rates->where("currency_id",2)->first();
            $xmr_rates = $rates->where("currency_id",3)->first();



      

            return view('browse.index')->with("listingAds",$listingAds)->with("listingsRandom",$listingsRandom)->with("categories",$categories)->with("news",$news)->with(["btc_rates"=>$btc_rates,"ltc_rates"=>$ltc_rates,"xmr_rates"=>$xmr_rates]);
        }

    public function exchange($exchange){
        $currency = array("usd","eur","gbp","aud","cad","brl","dkk","sek","nok","try","cny","hkd","rub","inr","jpy");
        foreach($currency as $sign) {
            if($exchange == $sign) {
                auth()->user()->currency = $sign;
                auth()->user()->save();
                return redirect()->back();
            }
        }
        return redirect()->back();
    }
}

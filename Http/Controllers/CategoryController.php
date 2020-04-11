<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Country;
use App\Models\ListingSearch;
use App\Models\CurrencyRates;
use App\Models\User;
use Carbon\Carbon;
use MetaTag;


class CategoryController extends Controller
{
    public function index($category_id,Request $request){

    

        $category = Category::find($category_id); 

        if($category == null) {
            abort(404);
        }

        $categories = Category::GetCategoriesWithTotalListings();
        $countries = Country::All();
        $chosen_category_key =  array_filter($categories,function ($v) use ($category){
            if($v->id == $category->id){
                return $v->id;
            }
        });

        $chosen_category = array_shift($chosen_category_key);

        $categories_clear = flatten($categories, 0);
        $list = [];
        foreach($categories_clear as $categoryd) {
            $list[''] = '';
            $list[$categoryd['id']] = str_repeat("&mdash;", $categoryd['depth']+1) . " " .$categoryd['name'];
        }

        $data['categories'] = $categories;
        $data['countries'] = $countries;
        $data['category_choosed'] = $category;

        
        

        if(isset($chosen_category->children) && count($chosen_category->children) != 0) {
            $data['listings'] = Listing::whereIn('category_id',Category::children($chosen_category))->orWhere('category_id',$category_id)->active()->orderBy('priority_until', 'DESC')->paginate(10);
        } else {
            $data['listings'] = $category->listings()->orderBy('priority_until', 'DESC')->active()->where('category_id',$category_id)->paginate(10);
        }

        $breadcrumbs = [];

        MetaTag::set('title', __($category->name));

        if($category) {
            $i = 0;
			while($category = $category->child) {
                $breadcrumbs[] = $category;
				$i ++;
				if($i == 5)
					break;
            }
        }
       
        $data['breadcrumbs'] = array_reverse($breadcrumbs);
        
        if($request->all() && !$request->input('page')) {
            $data['listings'] = ListingSearch::apply($request);
        }

        $rates = new CurrencyRates();

        $btc_rates = $rates->select(auth()->user()->currency,auth()->user()->currency.'_atl')->where("currency_id",1)->first();
        $ltc_rates = $rates->select(auth()->user()->currency,auth()->user()->currency.'_atl')->where("currency_id",2)->first();
        $xmr_rates = $rates->select(auth()->user()->currency,auth()->user()->currency.'_atl')->where("currency_id",3)->first();

        $currency  = auth()->user()->currency;
        $data['btc_rate'] = $btc_rates == null ? 0 : $btc_rates->$currency;
        $data['ltc_rate'] = $ltc_rates == null ? 0 : $ltc_rates->$currency;
        $data['xmr_rate'] = $xmr_rates == null ? 0 : $xmr_rates->$currency;
        $data['btc_atl'] = $btc_rates == null ? 0 : $btc_rates->$currency.'_atl';
        $data['ltc_atl'] = $ltc_rates == null ? 0 : $ltc_rates->$currency.'_atl';
        $data['xmr_atl'] = $xmr_rates == null ? 0 : $xmr_rates->$currency.'_atl';

        return view('category.index',$data);
    }


}


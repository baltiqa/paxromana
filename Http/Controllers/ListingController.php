<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Country;

use MetaTag;

class ListingController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($listing, $slug, Request $request)
    {
	
        $data = [];
        $visible_listing = $listing->is_published && !$listing->is_disabled;
        $can_edit = auth()->check() && (auth()->user()->id == $listing->user_id || auth()->user()->can('edit listing'));

        if(!$visible_listing && !$can_edit) {
            return abort(404);
        }

		$breadcrumbs = [];
        $category = $listing->category;


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


        $listing->load('shipping_options');
        
        $listing->views_count = $listing->views_count + 1;
        $listing->save();

        $data['listing'] = $listing;

        $data['tags'] = explode(",", $listing->tags);


        $data['seller'] = $listing->user;
        $data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->get();
        $data['ships_from'] = Country::where('country_short_name',$listing->shipped_from)->first();
        $countryDb = Country::whereIn('country_short_name',unserialize($listing->ships_to))->take(12)->get()->pluck('country_name')->toArray();
        $countries = implode (", ",$countryDb);
        $data['ships_to'] = $countries;

        MetaTag::set('title', $listing->title);
     
        return view('listing.show', $data);
    }

	public function card($listing, $slug, Request $request) {
		
    }

    public function star($listing) {
        $listing->toggleFavorite();
        return redirect()->back()->with('message',__('validation.favorite_now'));
    }


    public function follow($listing) {
        if(auth()->user()->getIsFollowed($listing->user)) {
            auth()->user()->unfollow($listing->user);
            return redirect()->back()->with('message',__('validation.follow_now').' '.$listing->user->username);
        } else {
            auth()->user()->follow($listing->user);
            return redirect()->back()->with('message',__('validation.unfollowed').' '.$listing->user->username);
        }
    }

    public function spotlight($listing) {
        if(auth()->user()->can('disable listing')) {
            $listing->toggleSpotlight();
        }
        return redirect(route('listing', [$listing, $listing->slug]));
    }
    public function verify($listing) {
        if(auth()->user()->can('disable listing')) {
            if($listing->is_admin_verified && !$listing->is_disabled) {
                $listing->is_disabled = Carbon::now();
            } elseif($listing->is_admin_verified && $listing->is_disabled) {
                $listing->is_disabled = null;
            }


            $listing->save();
        }
        return redirect(route('listing', [$listing, $listing->slug]));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($listing)
    {

        return view('create..partials.details', $data);
    }
	
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use Response;


class ApiController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth.apikey');
    }

    public function search(Request $request) {
    
        $listings = new Listing();
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'))->select('id','title','user_id');
            
        } 

        $listings = $listings->paginate(20);

        return response()->json(['listings'=>$listings]);

    }

    public function listing($id) {
        $listing = Listing::find($id);

        if($listing == null) {
            abort(404);
        }

        return response()->json([
            'title' => $listing->title,
            'vendor' => $listing->user->username,
            'description' => $listing->description,
            'listing_price' => $listing->price,
            'listing_currency' => $listing->currency,
            'listing_class' => $listing->listing_class_id == 1 ? 0 : 1,
            'escrow_type' => $listing->payment_type_id == 2 ? 0 : $listing->payment_type_id == 1 ? 1 : 2,
            'terms' => $listing->user->terms_conditions,
            'image_1' => $listing->photo,
            'image_2' => $listing->photo2,
            'image_3' => $listing->photo3,
            'search_tags' => $listing->tags,
            'shipping_options' => $listing->shipping_options,
            'shipping_origin' => $listing->shipped_from,
            'shipping_destination' => unserialize($listing->ships_to),
            'supported_btc' => $listing->user->support_bitcoin,
            'supported_ltc' => $listing->user->support_litecoin,
            'supported_xmr' => $listing->user->support_monero,
            ]);
    }

    public function vendor($username) {
        $user = User::where('username', $username)->where('trader_type','individual')->first();

        if($user== null) {
            abort(404);
        }

        return response()->json([
            'username' => $user->username,
            'link' => '/profile/'.$user->username,
            'avatar' => $user->avatar,
            'about' =>  $user->body,
            'join_date' => $user->created_at->diffForHumans(null, true). ' active on Pax Romana',
            'average_rating' => $user->avg_rating(),
            'sales' => $user->orders->count(),
            'pgp_key' => $user->pgp_key,
            ]);
    }

    public function feedback($username) {

        $user = User::where('username', $username)->where('trader_type','individual')->first();

        if($user== null) {
            abort(404);
        }

        $feedbacks = $user->comments()->get();

        return response()->json(['feedbacks'=>$feedbacks]);

    }

}

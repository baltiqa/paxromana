<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use MetaTag;
use Validator;


class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        MetaTag::set('id', "3");
        MetaTag::set('title', __('messages.header_my_favorites'));
        

		$user = User::find(auth()->user()->id);
		$favorites = $user->favorites()->where('favoriteable_type', Listing::class)->with('favoriteable')->get()->mapWithKeys(function ($item) {
			$tmp = $item['favoriteable'];
			$tmp['user'] = $item['user'];
            return [$item['favoriteable']->id=>$tmp];
        });

		return view('account.favorites', compact('user', 'favorites'));
    }

    public function show() {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'favorites' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $favorites_remove = $request->input('favorites');


            foreach($favorites_remove as $favorite_remove) {
               $listing = Listing::Find($favorite_remove);
                if($listing != null) {
                    $listing->toggleFavorite();
                } else {
                    abort(404);
                }
           }

        return redirect()->back()->with('message','');


    }
}
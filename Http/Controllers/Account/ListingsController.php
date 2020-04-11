<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use MetaTag;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        MetaTag::set('title', __('messages.account_head_vendor_2'));
        MetaTag::set('vendor_id', "2");
        MetaTag::set('id', "2");

        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        $list = [];

        $data['listings'] = Listing::where('user_id', auth()->user()->id)->where('parent_id','0')->orderBy('views_count', 'DESC')->get();
        $data['listings_deleted_count'] = Listing::onlyTrashed()->where('user_id', auth()->user()->id)->count();

		return view('account.listings',$data);
    }

    public function trashedListings() {
        MetaTag::set('title', __('messages.account_listings_trashed'));
        MetaTag::set('vendor_id', "2");
        MetaTag::set('id', "2");

        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        $list = [];

        $data['listings'] = Listing::onlyTrashed()->where('user_id', auth()->user()->id)->orderBy('views_count', 'DESC')->get();

		return view('account.trashedlistings',$data);
    }

    public function restore($id) {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }



        $listing = Listing::where('id',$id)->withTrashed()->first();
        if($listing == null) {
            abort(404);
        }



        if($listing->user_id != auth()->user()->id) {
            abort(404);
        }


        if($listing->deleted_at == null) {
            abort(404);
        }

        $listing->restore();

        return redirect()->back()->with('message',__('validation.listing_restored'));

    }

    public function permanentDelete(Request $request){
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        if($request->input('ids')) {
                   Listing::withTrashed()->whereIn('id', $request->input('ids'))->where('user_id', auth()->user()->id)->forceDelete();
                   return redirect()->back()->with('message',__('validation.listing_removedreal'));
        }

        return redirect()->back();
    }

    public function removeAll(Request $request) {


        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }
        
        if($request->input('ids')) {
                    Listing::whereIn('id', $request->input('ids'))->where('user_id', auth()->user()->id)->delete();
                    return redirect()->back()->with('message',__('validation.listing_removedtemporary'));
        }

        return redirect()->back();

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

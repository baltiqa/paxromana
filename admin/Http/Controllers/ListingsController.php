<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Category;
use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->is_admin != 1){
            abort(404);
        }


        $listings = new Listing();
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'));
        } else {
			$listings = $listings->orderBy('created_at', 'desc');
        }
        $data['listings'] = $listings->withTrashed()->paginate(100);
        $data['listings_count'] = Listing::count();

        return view('panel::listings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if(auth()->user()->is_admin != 1){
            abort(404);
        }


        return view('panel::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        if(auth()->user()->is_admin != 1){
            abort(404);
        }

        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($listing, FormBuilder $formBuilder)
    {
        if(auth()->user()->is_admin != 1){
            abort(404);
        }


		if(true) {
			$data = [];
			$data['listing'] = $listing;
			$data['dropdown'] = Category::attr(['name' => 'category_id', 'class'=>  'form-control'])
									->placeholder(0, '--SELECT--')
									->orderBy('order', 'ASC')
									->nested()
									->selected($listing->category_id)
									->renderAsDropdown();
			$data['form'] = $formBuilder->create(\Modules\Panel\Forms\ListingForm::class, [
				'method' => 'PUT',
				'url' => route('panel.listings.update', $listing),
				'model' => $listing
			]);			
			return view('panel::listings.edit', $data);
		}
        return redirect($listing->url);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($listing, Request $request)
    {

        if(auth()->user()->is_admin != 1){
            abort(404);
        }
        
        $listing->category_id = $request->input('category_id');
        
        if($request->input('deleted_at')) {
            $listing->deleted_at = Carbon::now();
        } else {
            $listing->deleted_at = null;
        }


        $listing->save();
		
        return redirect()->route('panel.listings.index')->with('message','Saved');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
       
    }
}

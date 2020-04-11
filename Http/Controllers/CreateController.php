<?php

namespace App\Http\Controllers;

use App\Models\ListingPlan;
use App\Models\ListingShippingOption;
use App\Models\ListingClass;
use App\Models\Country;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Support\Facades\Input;

use DB;
use Validator;
use MetaTag;
use File;

use Imagick;

class CreateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        if(auth()->user()->trader_type != 'individual') {
            return redirect('/account/apply_vendor');
        }

        MetaTag::set('title', __('messages.listing_create_title'));

        $data = [];
        
        $countries = Country::All();
       
        $categories = Category::nested()->get();

        $data['categories'] = $categories;
        $data['own_listing'] = Listing::where('user_id',auth()->user()->id)->where('parent_id','0')->get();

        $data['countries'] = $countries;

        $data['form'] = 'create';

        $view = 'listing.create.create_listing';

        return view($view, $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    #public function store(StoreListing $request)
    public function store(Request $request)
    {
		$rules = array(
			'title'		=>'required',
			'description'	=>'required',
			'category'		=> 'required',
			'price'			=> 'required|numeric',
            'ships_to'		=> 'required',	
            'shipped_from'		=> 'required',	
			'postage_shipping_1'		=> 'required',
			'postage_option_1'		=> 'required',
			'postage_price_1'		=> 'required|numeric',
            'image_1' => 'nullable|mimes:jpg,jpeg,png',
            'image_2' => 'nullable|mimes:jpg,jpeg,png',
            'image_3' => 'nullable|mimes:jpg,jpeg,png',
            'quantity'			=> 'required|numeric',

        );
              
        for ($i=2; $i <=8 ; $i++) { 
        	$postage_shipping = $request->input('postage_shipping_'.$i);
        	$postage_option = $request->input('postage_option_'.$i);
        	$postage_price = $request->input('postage_price_'.$i);
        	if(!empty($postage_shipping) || !empty($postage_option) || !empty($postage_price))
        		$rules += array(					
					'postage_shipping_'.$i		=> 'required',
					'postage_option_'.$i		=> 'required',
					'postage_price_'.$i		=> 'required|numeric'
		        );
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {            
            return redirect()->back()->withInput()->withErrors($validator);
        }  

        if ($request->hasFile('image_1')){
        	$size = $request->file('image_1')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }


        if ($request->hasFile('image_2')){
        	$size = $request->file('image_2')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        if ($request->hasFile('image_3')){
        	$size = $request->file('image_3')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        $cat = Category::Find($request->input('category'));
        if($cat == null) {
           return redirect()->back()->with('error',__('validation.listing_error'));
        }


        $country_short_names = Country::whereIn('country_short_name', $request->input('ships_to'))->count();
        if(count($request->input('ships_to')) > $country_short_names || $country_short_names == null  ) {
            return redirect()->back()->with('error',__('validation.listing_error'));
        }

        //save
        $listing = new Listing();
        $listing->user_id = auth()->user()->id;
        $listing->title = $request->input('title');
        $listing->description = $request->input('description');
        $listing->tags = $request->input('tags');
        $listing->category_id = $request->input('category');
        $listing->listing_class_id = $request->input('listingclass') == 1 ? 1 : 2;
        if($listing->listing_class_id == 2 && $request->input('autodispatch')) {
            $listing->autodispatch = $request->input('autodispatch');
         } elseif($listing->listing_class_id == 1) {
            $listing->autodispatch = "";
         }
        $listing->is_published = $request->input('status') == 1 ? 1 : 0;

        if(auth()->user()->has_fe != 1 ) {
            if($request->input('escrow') == 2 ) {
                redirect()->back()->with('error',__('validation.listing_error'));
           } else {
              $listing->payment_type_id = $request->input('escrow');
           }
        } 

        if(auth()->user()->has_fe == 1) {
            if($request->input('escrow') == 2) {
                $listing->payment_type_id = 2;
            }
        } else {
            switch($request->input('escrow')) {
                case 1:
                    $listing->payment_type_id = 1;
                break;

                case 4:
                    $listing->payment_type_id = 4;
                break;
                default:
                redirect()->back()->with('error',__('validation.listing_error'));
            }
        }

   
        $currency = array("usd","eur","gbp","aud","cad","brl","dkk","sek","nok","try","cny","hkd","rub","inr","jpy");
        if(in_array($request->input('currency'),$currency)) {
           $listing->currency = $request->input('currency');
        } 


        $listing->price = $request->input('price');
   
        $listing->quantity = $request->input('quantity');
        $listing->shipped_from = $request->input('shipped_from');
        $listing->ships_to =  serialize($request->input('ships_to'));

        $parent = $request->input('parent');



        if($parent == 'nope') {
            $listing->parent_id = 0;
        } else {
           if(in_array($request->input('parent'),auth()->user()->listings->pluck('id')->toArray())) {
            if($listing->childs->count() != 0) {
                return redirect()->back()->with('error',__('validation.cant_be_parent'));
            }
           $parentlisting = Listing::Find($request->input('parent'));
           if($listing == null) {
               return redirect()->back()->with('error',__('validation.listing_error'));
           } else {
               if($parentlisting->parent_id != 0) {
                   return redirect()->back()->with('error',__('validation.only_parent_copy'));
               } else {
                   $listing->parent_id = $request->input('parent');
               }
           }
           } else {
               return redirect()->back()->with('error',__('validation.listing_error'));
           }
        }

        if($listing->save()){
        	//save postage        	
        	for ($i=1; $i <=8 ; $i++) { 
	        	$postage_shipping = $request->input('postage_shipping_'.$i);
	        	$postage_option =  $request->input('postage_option_'.$i);
	        	$postage_price =  $request->input('postage_price_'.$i);
	        	if(!empty($postage_shipping) && !empty($postage_option) & !empty($postage_price)){
	        		$postage = new ListingShippingOption();
	        		$postage->listing_id = $listing->id;
	        		$postage->name = $postage_option;
                    $postage->days = $postage_shipping;
                    $postage->price = $postage_price;
	        		$postage->save();
                }
            }
            
             //save image
         $destinationPath = public_path().'/uploads/listings_images/';
         if ($request->hasFile('image_1')){
            $file = $request->file('image_1');

            $filename_ = str_random(32);

            $file_uploaded=$filename_.'.jpg';

            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }

            if($hoi) {
                $listing->photo =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }

        if ($request->hasFile('image_2')){
            $file = $request->file('image_2');

            $filename_ = str_random(32);

            $file_uploaded=$filename_.'.jpg';

            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }

            if($hoi) {
                $listing->photo2 =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }

        if ($request->hasFile('image_3')){
            $file = $request->file('image_3');

            $filename_ = str_random(32);

            $file_uploaded=$filename_.'.jpg';

            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }

            if($hoi) {
                $listing->photo3 =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }

	              
        }

       return redirect()->route('listing', ['id' => $listing, 'slug' => $listing->title])->with('message',__('validation.listing_created'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('create::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */

     public function disable($listing) {
        $listing = Listing::find($listing->id);

        if($listing != null && $listing->user_id == auth()->user()->id) {
              $listing->is_published = 0;
              $listing->save();
              return redirect()->back()->with('message',__('validation.listing_disabled'));
        }
        abort(404);
    }


    public function enable($listing) {
        $listing = Listing::find($listing->id);

        if($listing != null && $listing->user_id == auth()->user()->id) {
            $listing->is_published = 1;
            $listing->save();
            return redirect()->back()->with('message',__('validation.listing_enabled'));
        }
        abort(404);
    }


    public function clone($listing) {

        $listing = Listing::find($listing->id);
        if($listing != null && $listing->user_id == auth()->user()->id) {
            if($listing->parent_id != 0) {
                return redirect()->back()->with('error',__('validation.copy_listing'));
            }
        $clone_listing = $listing->replicate();
            if($clone_listing->save()) {
                $listing_postages = ListingShippingOption::where('listing_id',$listing->id)->get();		
	        	foreach ($listing_postages as $postage){ 
	        		$clone_postage = new ListingShippingOption();	
	        		$clone_postage->listing_id = $clone_listing->id;	        		
	        		$clone_postage->name = $postage->name;
                    $clone_postage->days = $postage->days;
                    $clone_postage->price = $postage->price;
	        		$clone_postage->save();	        	
                }	
                $clone_listing->title = "Cloned "  .  $listing->title;
                $clone_listing->views_count = 0;
                $clone_listing->created_at = Carbon::now();
                $clone_listing->spotlight = null;
                $clone_listing->priority_until = null;
                $clone_listing->bold_until = null;
                $clone_listing->parent_id = $listing->id;

                $clone_listing->save();
                return redirect()->route('listing', ['id' => $clone_listing, 'slug' => $clone_listing->title])->with('message',__('validation.copy_listing_yes'));
            } else {
                return redirect()->back()->withInput()->with('error',__('validation.image_size'));
            }
        }
        abort(404);
    }

    public function edit($listing)
    {
        MetaTag::set('title', __('messages.listing_edit_title'));

        if(auth()->user()->id != $listing->user_id) {
            abort(404);
        }

        $data = [];
        $data['listing'] = $listing;

        $categories = Category::nested()->get();

        $data['categories'] = $categories;

        $countries = Country::All();
        $data['countries'] = $countries;

        $data['own_countries'] = unserialize($listing->ships_to);
        $data['own_listing'] = Listing::where('user_id',auth()->user()->id)->where('parent_id','0')->get();

        $data['countries'] = $countries;


        return view('create.edit', $data);
    }


 

 

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($listing, Request $request)
    {
        if(auth()->user()->id != $listing->user_id) {
            abort(404);
        }

        if($request->input('parent') == null) {
            $request->merge(array('parent' => 'nope'));
        }

        if($request->input('photo2')) {
            if($listing->photo2 != null) {
                File::delete(public_path().$listing->photo2);
                $listing->photo2 = "";
                $listing->save();
                return redirect()->back();
            }
            return redirect()->back();
        }

        if($request->input('photo3')) {
            if($listing->photo3 != null) {
                File::delete(public_path().$listing->photo3);
                $listing->photo3 = "";
                $listing->save();
                return redirect()->back();
            }
            return redirect()->back();
        }

        $rules = array(
			'title'		=>'required',
			'description'	=>'required',
			'category'		=> 'required',
            'price'			=> 'required|numeric',
			'quantity'			=> 'required|numeric',
            'ships_to'		=> 'required',	
            'shipped_from'		=> 'required',	
            'image_1' => 'nullable|mimes:jpg,jpeg,png',
            'image_2' => 'nullable|mimes:jpg,jpeg,png',
            'image_3' => 'nullable|mimes:jpg,jpeg,png',
            'parent'		=> 'required',				
        );

        if($request->input('escrow') == 4) {
            if(auth()->user()->multisig_key_pub == null || auth()->user()->address_refunds == null ) {
                return redirect()->back()->withInput()->with('error',__('validation.no_multisig'));
            }
        }


        $postage_shipping_1 = $request->input('postage_shipping_1');
        if($postage_shipping_1)  {
        	$rules += array(					
				'postage_shipping_1'		=> 'required',
				'postage_option_1'		=> 'required',
				'postage_price_1'		=> 'required|numeric',
	        );
        } 

        for ($i=2; $i <=4 ; $i++) { 
            $postage_shipping =$request->input('postage_shipping_'.$i);
        	$postage_option = $request->input('postage_option_'.$i);
        	$postage_price = $request->input('postage_price_'.$i);
        	if(!empty($postage_shipping) || !empty($postage_option) || !empty($postage_price))
        		$rules += array(					
					'postage_shipping_'.$i		=> 'required',
					'postage_option_'.$i		=> 'required',
					'postage_price_'.$i		=> 'required|numeric'
                );
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {            
            return redirect()->back()->withInput()->withErrors($validator);
        }  

        if ($request->hasFile('image_1')){
        	$size = $request->file('image_1')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        if ($request->hasFile('image_2')){
        	$size = $request->file('image_2')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        if ($request->hasFile('image_3')){
        	$size = $request->file('image_3')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }



        $country_short_names = Country::whereIn('country_short_name', $request->input('ships_to'))->count();
        if(count($request->input('ships_to')) > $country_short_names || $country_short_names == null  ) {
            return redirect()->back()->with('error',__('validation.listing_error'));
        }

 //save
 $listing = Listing::find($listing->id);
 if(isset($listing->id)){      
  	
     $listing->title = $request->input('title');
     $listing->description = $request->input('description');
     $listing->tags = $request->input('tags');
     $listing->category_id = $request->input('category');
    
     $listing->listing_class_id = $request->input('listingclass') == 1 ? 1 : 2;
     if($listing->listing_class_id == 2 && $request->input('autodispatch')) {
        $listing->autodispatch = $request->input('autodispatch');
     } elseif($listing->listing_class_id == 1) {
        $listing->autodispatch = "";
     }

     $listing->is_published = $request->input('status') == 1 ? 1 : 0;

     $parent = $request->input('parent');

     $cat = Category::Find($request->input('category'));
     if($cat == null) {
        return redirect()->back()->with('error',__('validation.listing_error'));
     }


     if($parent == 'nope') {
         $listing->parent_id = 0;
     } else {
        if(in_array($request->input('parent'),auth()->user()->listings->pluck('id')->toArray())) {
            if($listing->childs->count() != 0) {
                return redirect()->back()->with('error',__('validation.cant_be_parent'));
            }
        $parentlisting = Listing::Find($request->input('parent'));
        if($listing == null) {
            return redirect()->back()->with('error',__('validation.listing_error'));
        } else {
            if($parentlisting->parent_id != 0) {
                return redirect()->back()->with('error',__('validation.cant_be_parent'));
            } else {
                $listing->parent_id = $request->input('parent');
            }
        }
        } else {
            return redirect()->back()->with('error',__('validation.listing_error'));
        }
     }



     




     if(auth()->user()->has_fe == 1) {
        if($request->input('escrow') == 2) {
            $listing->payment_type_id = 2;
        }
    } else {
        switch($request->input('escrow')) {
            case 1:
                $listing->payment_type_id = 1;
            break;

            case 4:
                $listing->payment_type_id = 4;
            break;
            default:
            redirect()->back()->with('error',__('validation.listing_error'));
        }
    }


     $currency = array("usd","eur","gbp","aud","cad","brl","dkk","sek","nok","try","cny","hkd","rub","inr","jpy");
     if(in_array($request->input('currency'),$currency)) {
        $listing->currency = $request->input('currency');
     } 
     
     $listing->price = $request->input('price');

     $listing->quantity = $request->input('quantity');
     $listing->shipped_from = $request->input('shipped_from');
     $listing->ships_to =  serialize($request->input('ships_to'));
     if($listing->save()){
         //save postage 
         $count_postage = 0;  
         $listing_postages = ListingShippingOption::where('listing_id',$listing->id)->get();		
         foreach ($listing_postages as $postage){
             $postage_shipping = $request->input('postage_shipping_id_'.$postage->id);
             $postage_option = $request->input('postage_option_id_'.$postage->id);
             $postage_price = $request->input('postage_price_id_'.$postage->id);
             if(!empty($postage_shipping) && !empty($postage_option) & !empty($postage_price)){
                 $postage = ListingShippingOption::find($postage->id);       		
                 $postage->name = $postage_option;
                 $postage->days = $postage_shipping;
                 $postage->price = $postage_price;
                 $postage->save();
             } 
             $count_postage++;
         }	

         for ($i=$count_postage+1; $i <=8 ; $i++) { 
             $postage_shipping = $request->input('postage_shipping_'.$i);
             $postage_option = $request->input('postage_option_'.$i);
             $postage_price = $request->input('postage_price_'.$i);
             if(!empty($postage_shipping) && !empty($postage_option) & !empty($postage_price)){
                 $postage = new ListingShippingOption();
                 $postage->listing_id = $listing->id;
                 $postage->name = $postage_option;
                 $postage->days = $postage_shipping;
                 $postage->price = $postage_price;
                 $postage->save();
             }
         }

        


         //save image
         $destinationPath = public_path().'/uploads/listings_images/';
         if ($request->hasFile('image_1')){

            $file = $request->file('image_1');

            $filename = str_random(32);

            $file_uploaded=$filename.'.png';

            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
                File::delete($destinationPath.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }
            
            if($listing->photo != null) {
                File::delete(public_path().$listing->photo);
            }

            if($hoi) {
                $listing->photo =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }


        if ($request->hasFile('image_2')){

            $file = $request->file('image_2');

            $filename = str_random(32);

            $file_uploaded=$filename.'.jpg';

            
            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
                File::delete($destinationPath.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }
            
            if($listing->photo2 != null) {
                File::delete(public_path().$listing->photo2);
            }

            if($hoi) {
                $listing->photo2 =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }

        if ($request->hasFile('image_3')){

            $file = $request->file('image_3');

            $filename = str_random(32);

            $file_uploaded=$filename.'.jpg';

            
            $upload_success  = $file->move($destinationPath, $file_uploaded); 

            if($upload_success) {
                $i = new Imagick($destinationPath.$file_uploaded);
                $i->stripImage();
                $noexif = '66'.$file_uploaded;
                $hoi = $i->writeImage($destinationPath.'66'.$file_uploaded);
                File::delete($destinationPath.$file_uploaded);
            } else {
                redirect()->back()->withInput()->with('error',__('validation.image_error'));
            }
            
            if($listing->photo3 != null) {
                File::delete(public_path().$listing->photo3);
            }

            if($hoi) {
                $listing->photo3 =  '/uploads/listings_images/'.$noexif;
                $listing->save();                                            
            }
        }


         
     }
     return redirect()->back()->with('message', __('validation.listing_updated'). ' <a href="/listing/'.$listing->hash.'/'.$listing->title.'">-></a>');
 }else redirect()->back()->withInput()->with('error',__('validation.image_error'));   


        return back();
    }

    public function deleteShippingOption($listing,$id, Request $request) {
        if(auth()->user()->id != $listing->user_id) {
            abort(404);
        }
        $postage = ListingShippingOption::find($id);
        
        if($postage == null || $postage->listing_id != $listing->id) {
            abort(404);
        }

        $postage->delete();

      return  redirect()->back()->withInput()->with('message',__('validation.postage_removed'));   

    }


}

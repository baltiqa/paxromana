<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Models\CurrencyRates;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PGP_2FA;
use App\Models\BuyLog;
use App\Models\ServerCredentials;
use App\Bitcoin\Bitcoin;
use App\Litecoin\Litecoin;
use App\Models\UserAddresses;
use App\Monero\Monero;
use App\Http\Controllers\MultisigController;
use App\Http\Controllers\NotificationController;


use MetaTag;
use Validator;
class ProfileController extends Controller
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
    public function index()
    {
        MetaTag::set('title', __('messages.header_my_account'));
        MetaTag::set('id', "1");

		return view('account.profile');
    }


    public function feedbacks()
    {
        if(auth()->user()->trader_type != "individual") {
            abort(404);
        }
        
        MetaTag::set('title', __('messages.account_head_vendor_5'));
        MetaTag::set('vendor_id', 3);
        MetaTag::set('id', "2");
    
		return view('account.feedbacks');
    }

    public function edit() {
        MetaTag::set('id', "1");
        MetaTag::set('normal_id', "2");
        MetaTag::set('title', __('messages.profile_block_1'));


        return view('account.edit_profile');
    }

    public function pgp_key() {
        MetaTag::set('id', "1");
        MetaTag::set('title', __('messages.profile_pub_key'));
        MetaTag::set('normal_id', "3");

        return view('account.edit_pgp');
    }

   


    public function generate2FACode($pgp_key) {

        $pgp_2fa = new PGP_2FA();

      $pgp_2fa->generateSecret();  
      $message = $pgp_2fa->encryptSecret($pgp_key);
     
        return $message;
    }


    public function pgpUpdate(Request $request) {

        if(!$request->input('pgp_key')) {
            return redirect()->back()->with('error',__('validation.invalid_pgp_key'));
        }
        
         $pgp_message = $this->generate2FACode($request->input('pgp_key'));


        session()->put('suckmynuts', $pgp_message);
        session()->put('pgp_key', $request->input('pgp_key'));


        return redirect('/account/verify_pgp');
    }


    public function pgpVerifyPage() {
        MetaTag::set('id', "1");
        MetaTag::set('title', __('messages.profile_pub_key'));
        MetaTag::set('normal_id', "3");

        if(session()->get('pgp-secret-hash') == null ) {
            return redirect('/account/change_pgp');
        }

        return view('account.verify_pgp');
    }


    public function verifyPGP(Request $request) {
        $user = User::findOrFail(auth()->user()->id);

        $pgp_2fa = new PGP_2FA();

        if($pgp_2fa->compare($request->input('code'),$request->session()->get('pgp-secret-hash'))) {

            $user->pgp_key = $request->session()->get('pgp_key');
            $user->save();

            $request->session()->forget('suckmynuts');
            $request->session()->forget('pgp-secret-hash');
            $request->session()->forget('pgp_key');

           return redirect('/account/change_pgp')->with('message',__('validation.succesfull_saved'));
        } else {
            return back()->withErrors(['field_name' => [__('messages.invalid_code_pgp')]]);
        }
    }


    public function multisig() {

        MetaTag::set('id', "1");
        MetaTag::set('normal_id', "4");
        MetaTag::set('title', __('messages.feature_customer_title_2'));


        return view('account.multisig');
    }

    public function multisigUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'btcmultisig' => 'required',
            'btcrefund' => 'required',
        ]);

        $user = User::find(auth()->user()->id);
        $multisig = new MultisigController();


        if(auth()->user()->trader_type == "individual") {
            if($request->input('btcsales') == null) {
                return redirect()->back()->withErrors(['btcsales'=>'required']);
            } else {
                if(substr_startswith($request->input("btcsales"),"bc1") ) {
                    return redirect()->back()->with('error',__('validation.invalid_btc_address'));
                }
                
                $validate_sales = $multisig->ValidateAddress($request->input("btcsales"));
                if(!is_object($validate_sales) || !isset($validate_sales->result['address'])){
                    return redirect()->back()->with('error',__('validation.invalid_btc_address'));
                }
            }
        }

        if(substr_startswith($request->input("btcsales"),"bc1") ) {
            return redirect()->back()->with('error',__('validation.invalid_btc_address'));
        }
       
        if(strlen($request->input('btcmultisig')) != 32 && $request->input('btcmultisig') == ""){
            return redirect()->back()->with('error',__('validation.invalid_pub_key'));
        }
        $validate_refund = $multisig->ValidateAddress($request->input("btcrefund"));
        if(!is_object($validate_refund) || !isset($validate_refund->result['address'] )){
            return redirect()->back()->with('error',__('validation.invalid_btc_address'));
        }
       

        $user = User::find(auth()->user()->id);
        $user->multisig_key_pub = $request->input('btcmultisig');
        $user->address_refunds = $request->input('btcrefund');
        $user->address_sales = $request->input('btcsales');
        $user->save();

        return redirect()->back()->with('message',__('validation.succesfull_saved'));
    }


    public function vendor_settings() {
        if(auth()->user()->trader_type != "individual") {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_head_vendor_1'));
        MetaTag::set('vendor_id', "1");
        MetaTag::set('id', "2");

        return view('account.vendor_settings');
    }
    
    public function updateStore(Request $request) {
        if(auth()->user()->trader_type != "individual") {
            abort(404);
        }

        $user = User::Find(auth()->user()->id);
        if($request->input('terms')) {
            $user->terms_conditions = $request->input('terms');
        }
        

        switch($request->input('holiday')) {
            case 0:
            foreach($user->listings as $listing) {
                $listing->is_published = 1;
                $listing->save();
            }
            $user->on_vacation =0;
            break;
            case 1:
                foreach($user->listings as $listing) {
                    $listing->is_published = 0;
                    $listing->save();

                }
            $user->on_vacation=1;
            break;
        }

        $user->support_bitcoin = $request->input('bitcoin') == 1 ? 1 : 0;
        $user->support_monero = $request->input('monero') == 1 ? 1 : 0;
        $user->support_litecoin = $request->input('litecoin') == 1 ? 1 : 0;
 
        $user->save();


        return redirect()->back()->with('message',__('validation.succesfull_saved'));
    }


    public function storeProfile(Request $request)
    {
        $user = User::Find(auth()->user()->id);

        if ($request->hasFile('avatar_img')){
        	$size = $request->file('avatar_img')->getSize();
        	if(($size/1024) > 512) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        if ($request->hasFile('avatar_background_img')){
        	$size = $request->file('avatar_background_img')->getSize();
        	if(($size/1024) > 912) return redirect()->back()->withInput()->with('error',__('validation.image_size'));
        }

        $avatar_img = public_path().'/uploads/avatar_heads/';

        if ($request->hasFile('avatar_img')){
           $file = $request->file('avatar_img');

           $filename_ = str_random(25);
           $filename = preg_replace("/[^a-zA-Z0-9\.]/", "", strtolower($file->getClientOriginalName()));

           $file_uploaded=$filename_.'_'.$filename;

           $upload_success  = $file->move($avatar_img, $file_uploaded); 
       
           if( $upload_success ) {
               $user->avatar =  '/uploads/avatar_heads/'.$file_uploaded;
           }
       }

       $avatar_bg = public_path().'/uploads/avatar_backgrounds/';
       if ($request->hasFile('avatar_background_img')){
        $file = $request->file('avatar_background_img');

        $filename_ = str_random(25);
        $filename = preg_replace("/[^a-zA-Z0-9\.]/", "", strtolower($file->getClientOriginalName()));

        $file_uploaded=$filename_.'_'.$filename;

        $upload_success  = $file->move($avatar_bg, $file_uploaded); 
    
        if( $upload_success ) {
            $user->avatar_background =  '/uploads/avatar_backgrounds/'.$file_uploaded;
        }
     }

       $user->bio = $request->input('bio');

       $user->save();                                            
		
        return redirect()->back()->with('message',__('validation.succesfull_saved'));
    }


    public function applyVendor() {
        MetaTag::set('title', __('messages.profile_apply_vendor_title'));

        if(auth()->user()->trader_type == "individual") {
            return redirect()->back();
        }

        return view('account.vendor');
    }

    public function payVendor(Request $request) {
        MetaTag::set('title', __('messages.profile_apply_vendor_title'));

        $validator = Validator::make($request->all(), [
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

         if(auth()->user()->pgp_key == null) {
            return redirect()->back()->with('error',__('validation.add_pgp_key'));
        }

        $user = auth()->user();
        $currency = "usd";

        $notifications = new NotificationController();
        if($request->input('paybtc')) {
            $price = 150 / $this->btc_rates->$currency;
            if(auth()->user()->btcBalance($price)) {
                $user->trader_type= "individual";
                $user->save();
                $buylog = new BuyLog;
                $buylog->username = $user->username;
                $buylog->amount = $price;
                $buylog->type = "Vendorship";
                $buylog->currency = "BTC";
                $buylog->paid = 0;
                $buylog->save();
                $notifications->welcomeAsVendor($user->id);
                return redirect('/account/vendor_settings')->with('message',__('validation.active_vendor_now'));
                } else {
                return redirect()->back()->with('error',__('validation.btc_not_enough'));
                }
        }

        if($request->input('payltc')) {
            $price = 150 / $this->ltc_rates->$currency;
            if(auth()->user()->ltcBalance($price)) {
                $user->trader_type= "individual";
                $user->save();
                $buylog = new BuyLog;
                $buylog->username = $user->username;
                $buylog->amount = $price;
                $buylog->type = "Vendorship";
                $buylog->currency = "LTC";
                $buylog->paid = 0;
                $buylog->save();
                return redirect('/account/vendor_settings')->with('message',__('validation.active_vendor_now'));
                } else {
                return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                }
        }

        if($request->input('payxmr')) {
            $price = 150 / $this->xmr_rates->$currency;
            if(auth()->user()->xmrBalance($price)) {
                $user->trader_type= "individual";
                $user->save();
                $buylog = new BuyLog;
                $buylog->username = $user->username;
                $buylog->amount = $price;
                $buylog->type = "Vendorship";
                $buylog->currency = "XMR";
                $buylog->paid = 0;
                $buylog->save();
                return redirect('/account/vendor_settings')->with('message',__('validation.active_vendor_now'));
                } else {
                return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
        }

        return view('account.vendor');
    }


    
    public function GenerateAddresses($id){

        $btc_credentials = ServerCredentials::where("currency",1)->first();
        $bitcoin = new Bitcoin($btc_credentials->username,$btc_credentials->password,$btc_credentials->host,$btc_credentials->port);
        $ltc_credentials = ServerCredentials::where("currency",2)->first();
        $litetcoin = new Litecoin($ltc_credentials->username,$ltc_credentials->password,$ltc_credentials->host,$ltc_credentials->port);
        $xmr_credentials = ServerCredentials::where("currency",3)->first();
        $monero = new Monero($xmr_credentials->username,$xmr_credentials->password,$xmr_credentials->host,$xmr_credentials->port);

        $btc_address = $bitcoin->getnewaddress();
        $ltc_address = $litetcoin->getnewaddress();
        $xmr_address = $monero->create_address();

        $values = ["user_id" => $id,"btc_address"=>$btc_address->result,"ltc_address"=>$ltc_address->result,"xmr_address"=>$xmr_address->result['address']];
 
        UserAddresses::updateOrCreate(["user_id"=>$id],$values);

    }

    public function GetUserAddresses($id){

        $user_addresses = UserAddresses::where("user_id",$id)->first();

        return $user_addresses;
    }


}

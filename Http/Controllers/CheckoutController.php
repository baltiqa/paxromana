<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MultisigController;
use App\Models\CurrencyRates;
use App\Models\ListingShippingOption;
use App\Models\MultisigTransactions;
use App\Models\MultisigAddresses;
use App\Models\TempMultisig;
use Illuminate\Http\Request;
use App\Models\PGP_2FA;
use App\Models\Listing;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use MetaTag;
use GNUPG;


class CheckoutController extends Controller
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

    public function index()
    {    
        MetaTag::set('title', __('messages.checkout_title'));


        if(session()->get('quantity') == null) {
            return redirect()->route('browse');
        }

        return view('checkout.index');
    }

    
    public function checkout($listing, Request $request) {

        $validator = Validator::make($request->all(), [
            'shipping_option' => 'required',
            'quantity' => 'required|integer|between:1,99',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        
        $shipping_method = new \stdClass();
        if(in_array($request->input('shipping_option'), $listing->shipping_options->pluck('id')->toArray())) {
            $shipping_method = $listing->shipping_options->where('id',$request->input('shipping_option'))->first();
            session()->put('shipping_method', $shipping_method);
        } else {
            abort(404);
        }

        $price = ($listing->price * $request->input('quantity')) + $shipping_method->price;
        $currency = strtolower($listing->currency);
        switch($request->input('paymentmethod')) {
            case 1:
                $price = $price / $this->btc_rates->$currency;
                if($price > auth()->user()->btc_balance && $listing->payment_type_id != 4) {
                    return redirect()->back()->with('error',__('validation.btc_not_enough'));
                }
                session()->put('payment_method', $request->input('paymentmethod'));
            break;
            case 2:
                $price = $price / $this->ltc_rates->$currency;
                if($price > auth()->user()->ltc_balance) {
                    return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                }
                session()->put('payment_method', $request->input('paymentmethod'));
                break;
            case 3:
                $price = $price / $this->xmr_rates->$currency;
                if($price > auth()->user()->xmr_balance) {
                    return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
                session()->put('payment_method', $request->input('paymentmethod'));
            break;
            default:
            abort(404);
        }
    

        session()->put('quantity', $request->input('quantity'));
        session()->put('listing', $listing);

        $address = "";
        $btc_price = 0;
        $seller = User::find($listing->user_id);
        if($listing->payment_type_id == 4){

            if(strlen(auth()->user()->multisig_key_pub) < 65 || strlen(auth()->user()->address_refunds) < 25 ){
                return redirect()->back()->with('error',__('validation.invalid_pgp_or_address'));
            }

            if(strlen($seller->multisig_key_pub) < 65 || strlen($seller->address_refunds) < 25 ){
                return redirect()->back()->with('error',__('validation.seller_invalid_pgp'));
            }

            session()->forget('price');
            session()->put('price', $price);
            $multisig = new MultisigController();
            $fee = $multisig->GetTransactionFee();
            $btc_price = $price + $fee;
            $btc_price = number_format($btc_price,8);
            $listing->btc_price = $btc_price;
            $address = $multisig->CreateAddress("multisig",$price);
            if(!isset($address->address)){
                return redirect()->back()->with('error',__('validation.error'));
            }

            $temp_order = new TempMultisig();
            $temp_order->listing_id = $listing->id;
            $temp_order->buyer_id = auth()->user()->id;
            $temp_order->quantity = $request->input('quantity');
            $temp_order->shipping_id = $shipping_method->id;
            $temp_order->multisig_id = $address->multisig_id;
            $temp_order->save();
        }

        session()->forget('address');
        if(is_object($address)){
        $pgp = new PGP_2FA;

        $message = $address->address;


        session()->put('address_address', $address->address);

        session()->put('address', $message);
        }

        session()->put('btc_price', $btc_price);

        return redirect('checkout');
    }



    public function CheckMultisig($address){
        $multisig = new MultisigController();
        $content = array();
        if($multisig->CheckMultiSigAmount($address)){
            $content = "paid";
        }else{
            $content = "pending";
        }
        return $content;
    }

    public function placingOrder(Request $request) {
        $validator = Validator::make($request->all(), [
            'agremeent' => 'required',
            'shipping' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $listingOrder = $request->session()->get('listing');
        $user = User::find(auth()->user()->id);
        if($listingOrder == null) {
            abort(404);
        }



        $paymentmethod = $request->session()->get('payment_method');
        $total_amount = ($listingOrder->price * $request->session()->get('quantity')) + $request->session()->get('shipping_method')->price;
        switch($paymentmethod) {
            case 1:
                $currency = strtolower($listingOrder->currency);
                $btc_total = $total_amount / $this->btc_rates->$currency;
                if($listingOrder->payment_type_id != 4){ // do not apply for multisig
                    if($btc_total > $user->btc_balance ) {
                        return redirect()->back()->with('error',__('validation.btc_not_enough'));
                    }
                    $user->btc_balance = $user->btc_balance - $btc_total;
                   
                }else{
                    $multisig = new MultisigController();
                    if(!$multisig->CheckMultiSigAmount($request->session()->get("address_address"))){
                        return redirect()->back()->with('error',__('validation.multisig_not_received'));
                    }
                }
            break;
            case 2:
                $currency = strtolower($listingOrder->currency);
                $ltc_total = $total_amount / $this->ltc_rates->$currency;
                if($ltc_total > $user->ltc_balance) {
                    return redirect()->back()->with('error',__('validation.ltc_not_enough'));
                } 
                $user->ltc_balance = $user->ltc_balance - $ltc_total;
            break;
            case 3:
                $currency = strtolower($listingOrder->currency);
                $xmr_total = $total_amount / $this->xmr_rates->$currency;
                if($xmr_total > $user->xmr_balance) {
                    return redirect()->back()->with('error',__('validation.xmr_not_enough'));
                }
                $user->xmr_balance = $user->xmr_balance - $xmr_total;
            break;
        }
       
        $user->save();

        $message = '';
        if($listingOrder->user->pgp_key != null) {
         putenv("GNUPGHOME=/tmp");
 
           $gpg = new gnupg();
            $info = $gpg->import($listingOrder->user->pgp_key);
            $gpg->addencryptkey($info['fingerprint']);
            $message = $gpg->encrypt($request->input('shipping'));
        }

        $notifications = new NotificationController();

        
        $order = new Order;
        $order->listing_id = $listingOrder->id;
        $order->seller_id = $listingOrder->user_id;
        $order->user_id = $user->id;
        $order->auto_final = Carbon::now()->addDays(3);
        $order->shipping_id = $request->session()->get('shipping_method')->id;
        $order->status = 'processing';
        $order->product_title = $listingOrder->title;
        $order->is_digital = $listingOrder->listing_class_id == 1 ? 0 : 1;


        switch($paymentmethod) {
            case 1:
             $order->currency = "BTC";
            break;
            case 2:
            $order->currency = "LTC";
            break;
            case 3:
            $order->currency = "XMR";
            break;
        }


        $order->amount = $request->session()->get('quantity');
        $price  = (($listingOrder->price * $request->session()->get('quantity'))  + $request->session()->get('shipping_method')->price);
        $service_fee = (($listingOrder->price * $request->session()->get('quantity')) + $request->session()->get('shipping_method')->price) * ($listingOrder->user->commission/100);
        $currency = strtolower($listingOrder->currency);
        if($request->session()->get('payment_method') == 1) {
            $order->price = number_format($price / $this->btc_rates->$currency,8);
            $order->service_fee = number_format($service_fee / $this->btc_rates->$currency,8);
            $order->shipping_fee = number_format($request->session()->get('shipping_method')->price / $this->btc_rates->$currency,8);
        } elseif($request->session()->get('payment_method') == 3) {
            $order->price = number_format($price / $this->xmr_rates->$currency,8);
            $order->service_fee = number_format($service_fee / $this->xmr_rates->$currency,8);
            $order->shipping_fee = number_format($request->session()->get('shipping_method')->price / $this->xmr_rates->$currency,8);
        } elseif($request->session()->get('payment_method') == 2) {
            $order->price = number_format($price / $this->ltc_rates->$currency,8);
            $order->service_fee = number_format($service_fee / $this->ltc_rates->$currency,8);
            $order->shipping_fee = number_format($request->session()->get('shipping_method')->price / $this->ltc_rates->$currency,8);
        }
        $order->payment_type_id = $listingOrder->payment_type_id;
        $order->shipping_address = $message;

        if($order->listing->listing_class_id == 2 && strlen($order->listing->autodispatch) > 1) {
            $order->auto_final = Carbon::now()->addDays(1);
            $order->status = "shipped";
            $order->autodispatch = $listingOrder->autodispatch;
            $notifications->NotifyAutoDispatchNoteBuyer($order);
        }

        if($listingOrder->payment_type_id == 4){
        $order->is_multisig = 1;
        }
      
        $order->save();
        

        if($listingOrder->payment_type_id == 4){
            $notifications->notifyTranReceived(auth()->user()->id,$order->price,$order->currency);
            $addresses_record =  MultisigAddresses::where("address_2",$request->session()->get("address_address"))->first();
            MultisigTransactions::where("id",$addresses_record->multisig_id)->update(["order_id" => $order->id]);
            $multisig->CreateMultiSig($listingOrder,$user,$request->session()->get("address_address"));
        }


        $notifications->notifyOrderCreate(auth()->user()->id,$order->id);
        $notifications->notifyOrderReceived($listingOrder->user_id,$order->id);

        $request->session()->forget('shipping_method');
        $request->session()->forget('payment_method');
        $request->session()->forget('quantity');
        $request->session()->forget('listing');
        $request->session()->forget('address');
        $request->session()->forget('address_address');
        $request->session()->forget('btc_price');
        return redirect()->route('account.purchase-history.show',$order)->with('message', __('validation.order_successfully_placed'));

    }


    public function ContinueOrder($temp_id){

        $order = TempMultisig::where("id",$temp_id)->first();

        if(!$order){
            return redirect()->back()->with('error','Order doesn\'t exist or has already been completed.');
        }

        if(auth()->user()->id != $order->buyer_id){
            abort(404);
        }


        $listing = Listing::where("id",$order->listing_id)->first();

        $addresses_record =  MultisigAddresses::where("multisig_id",$order->multisig_id)->first();

        $shipping_method = ListingShippingOption::where('id',$order->shipping_id)->first();

        session()->put('shipping_method', $shipping_method);

        $price = ($listing->price * $order->quantity) + $shipping_method->price;

        session()->put('payment_method', 1);
        session()->put('quantity', $order->quantity);
        session()->put('listing', $listing);

        session()->forget('price');
        session()->put('price', $price);

        session()->forget('address');
        session()->put('address', $addresses_record->address_2);
        session()->put('complete_order', true);


        return redirect('checkout');

    }

}


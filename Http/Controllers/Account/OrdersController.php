<?php

namespace App\Http\Controllers\Account;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Notifications\Alert;
use App\Models\Order;
use App\Models\User;
use App\Models\Listing;
use Carbon\Carbon;

use App\Http\Controllers\MultisigController;
use App\Models\MultisigTransactions;
use App\Models\MultisigAddresses;
use App\Models\Referral;

use MetaTag;
use Talk;
use Validator;
use App\Rules\CaptchaCheck;

class OrdersController extends Controller
{

    public function index(Request $request)
    {

        MetaTag::set('title', __('messages.account_sale_head1'));
        MetaTag::set('sub_id', "11");
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");

        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->process()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->process()->orderBy('created_at', 'DESC')->paginate(10);
        }



        return view('account.orders.index', compact('orders'));
    }

    public function accepted(Request $request)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_sale_head2'));
        MetaTag::set('sub_id', "12");
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");
        MetaTag::set('state', "1");

        $listing = Listing::find(3);


        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->accepted()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->accepted()->orderBy('created_at', 'DESC')->paginate(10);
        }


        return view('account.orders.accepted', compact('orders'));
    }


    public function shipped(Request $request)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_sale_head3'));
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");
        MetaTag::set('sub_id', "13");
        MetaTag::set('state', "2");


        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->shipped()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->shipped()->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('account.orders.shipped', compact('orders'));
    }

    public function cancelled(Request $request)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_sale_head5'));
        MetaTag::set('sub_id', "14");
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");
        MetaTag::set('state', "5");

        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->cancelled()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->cancelled()->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('account.orders.cancelled', compact('orders'));
    }

    public function dispute(Request $request)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_sale_head4'));
        MetaTag::set('sub_id', "15");
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");
        MetaTag::set('state', "4");

        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->dispute()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->dispute()->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('account.orders.disputed', compact('orders'));
    }


    public function finalized(Request $request)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        MetaTag::set('title', __('messages.account_sale_head6'));
        MetaTag::set('sub_id', "16");
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");
        MetaTag::set('state', "3");

        $orders = new Order();

        if($request->get('q')) {
            $orders = $orders->search($request->get('q'))->where('seller_id', auth()->user()->id)->finalized()->orderBy('created_at', 'DESC')->paginate(10);
        } else {
			$orders = Order::with('listing','user')->where('seller_id', auth()->user()->id)->finalized()->orderBy('created_at', 'DESC')->paginate(10);
        }


        return view('account.orders.finalized', compact('orders'));
    }


    public function show($order)
    {
        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        if(auth()->user()->id != $order->seller_id ) {
                abort(404);
        }
        MetaTag::set('vendor_id', "5");
        MetaTag::set('id', "2");

        

        MetaTag::set('title', __('messages.salesdetail_title'));


        $this->authorize('update', $order);


        return view('account.orders.show', compact('order'));
    }

    public function updateAll(Request $request) {
        $notifications = new NotificationController();

        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        $ids = $request->input('ids');

        if($ids == null) {
            return redirect(route('account.orders.index'));
        }
        


        switch($request->input('status')) {
            case "accept":
                foreach($ids as $id) {
                    $order = Order::Find($id);
                    if($order != null) {
                        if(auth()->user()->id != $order->seller_id ) {
                            abort(404);
                         } else {
                             if($order->status == "processing") {
                                $order->status="accepted";
                                $order->auto_final = Carbon::now()->addDays(3);
                                $order->save();
                                $notifications->notifyOrderAccept($order->user_id,$order->id);
                             } else {
                                abort(404);
                             }
                         }
    
                    } else {
                        abort(404);
                    }
                }
                return redirect(route('account.orders.index'))->with('message',__('validation.order_accepted'));

            break;
            case "cancel":
                foreach($ids as $id) {
                    $order = Order::Find($id);
                    if($order != null) {
                        if(auth()->user()->id != $order->seller_id ) {
                            abort(404);
                         } else {
                             $buyer = User::find($order->user_id);
                             if($order->status == "processing") {
                                $order->status="cancelled";
                                $order->auto_final = Carbon::now();
                                $order->save();

                                if($order->payment_type_id == 4){
                                    $multisig = new MultisigController();
                                    $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                                    $multisig_sign = $multisig->SignMultiSigTransaction($multisig_record,$buyer->address_refunds);
                                    if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                                        $notifications->notifyOrderCancelled($order->user_id,$order->id,"multisig",$multisig_sign->result["hex"]);
                                        $order->save();
                                        $buyer->save();
                                    }else{
                                        return redirect()->back()->with('error',__('validation.error'));
                                    }
    
                                } else {
                                    switch($order->currency) {
                                        case "XMR":
                                           $order->user->xmr_balance = $order->user->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                                        break;
                                        case "BTC":
                                            $order->user->btc_balance = $order->user->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                        case "LTC":
                                            $order->user->ltc_balance = $order->user->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                    }
                                    $order->user->save();
                                }

                             } else if($order->status == "accepted") {
                                $order->status="cancelled";
                                $order->auto_final = Carbon::now();
                                $order->save();


                                if($order->payment_type_id == 4){
                                    $multisig = new MultisigController();
                                    $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                                    $multisig_sign = $multisig->SignMultiSigTransaction($multisig_record,$buyer->address_refunds);
                                    if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                                        $notifications->notifyOrderCancelled($order->user_id,$order->id,"multisig",$multisig_sign->result["hex"]);
                                        $order->save();
                                        $buyer->save();
                                    }else{
                                        return redirect()->back()->with('error',__('validation.error'));
                                    }
    
                                } else {
                                    switch($order->currency) {
                                        case "XMR":
                                           $order->user->xmr_balance = $order->user->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                        case "BTC":
                                            $order->user->btc_balance = $order->user->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                        case "LTC":
                                            $order->user->ltc_balance = $order->user->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                    }
                                    $order->user->save();
                                }
                             } else {
                                abort(404);
                             }
                         }
    
                    } else {
                        abort(404);
                    }
                }
                return redirect(route('account.orders.index'))->with('message',__('validation.order_cancelled'));
            break;
            case "ship":
                foreach($ids as $id) {
                    $order = Order::Find($id);
                    if($order != null) {
                        if(auth()->user()->id != $order->seller_id ) {
                            abort(404);
                         } else {
                             if($order->status == "accepted") {

                                if($order->payment_type_id == 4){
                                    $multisig = new MultisigController();
                                    $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                                    $multisig_addresses = MultisigAddresses::where("multisig_id",$multisig_record->id)->first();
                                    if(!$multisig->CheckMultiSigAmount($multisig_addresses->address_2,0)){
                                        return redirect()->back()->with('error',__('validation.order_not_enough_confim'));
                                    }
                                }

                                if($order->is_digital == 1) {
                                    $order->auto_final = Carbon::now()->addDays(1);
                                } elseif($order->is_digital == 0) {
                                    $order->auto_final = Carbon::now()->addDays(14);
                                }

                                $order->status="shipped";


                                 if($order->payment_type_id == 2){
                                    switch($order->currency) {
                                        case "XMR":
                                            $order->seller->xmr_balance = $order->seller->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                        case "BTC":
                                            $order->seller->btc_balance = $order->seller->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);  
                                        break;
                                        case "LTC":
                                            $order->seller->ltc_balance = $order->seller->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                                        break;
                                    }
                                    $notifications->notifyOrderShippedFinalized($order->user_id,$order->id);
                                     $order->auto_final = Carbon::now();
                                    $order->seller->save();
                                    $order->status="finalized";
                                 }

                                 if($order->payment_type_id != 2) {
                                    $notifications->notifyOrderShipped($order->user_id,$order->id);
                                 }

                                $order->save();
                             } else {
                                abort(404);
                             }
                         }
    
                    } else {
                        abort(404);
                    }
                }
                return redirect(route('account.orders.shipped'))->with('message',__('validation.order_shipped'));
            break;
        }


        return redirect()->back();
    }



    public function update(Request $request, $order)
    {

        $notifications = new NotificationController;


        if(auth()->user()->trader_type != "individual" ) {
            abort(404);
        }

        if(auth()->user()->id != $order->seller_id ) {
            abort(404);
         }


        switch($request->input('status')) {
            case "accept":
            $order->status="accepted";
            $order->auto_final = Carbon::now()->addDays(3);
            $notifications->notifyOrderAccept($order->user_id,$order->id);
            break;
            case "shipped":
            if($order->status == 'accepted') {
                $order->status="shipped";
                if($order->is_digital == 1) {
                    $order->auto_final = Carbon::now()->addDays(1);
                } elseif($order->is_digital == 0) {
                    $order->auto_final = Carbon::now()->addDays(14);
                }

                if($order->payment_type_id == 4){
                    $multisig = new MultisigController();
                    $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();

                    if(!$multisig->CheckMultiSigAmount($multisig_record->multisig_address,0)){
                        return redirect()->back()->with('error',__('validation.order_not_enough_confim'));
                    }
                }


                $details = [
                    'message' => "Your order  #".$order->hash." has been shipped by the vendor ".$order->seller->username,
                    'image' => $order->seller->avatar,
                    'url'   => "/account/purchase-history/".$order->hash,
                    'vendor'   => ""
                 ];

                 if($order->payment_type_id == 2){
                    switch($order->currency) {
                        case "XMR":
                            $order->seller->xmr_balance = $order->seller->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                        break;
                        case "BTC":
                            $order->seller->btc_balance = $order->seller->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);  
                        break;
                        case "LTC":
                            $order->seller->ltc_balance = $order->seller->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                        break;
                    }
                    $details = [
                        'message' => "Your order  #".$order->hash." has been shipped by the vendor ".$order->seller->username.' This vendor has FE rights,therefore the order is now finalized.',
                        'image' => $order->seller->avatar,
                        'url'   => "/account/purchase-history/".$order->hash,
                        'vendor'   => ""
                     ];

                    $order->auto_final = Carbon::now();
                    $order->seller->save();
                    $order->status="finalized";
                }
        
                $order->user->notify(new Alert($details));
            }
            break;
            case "cancel":
            if($order->status == 'processing') {
                $order->status="cancelled";
                $details = [
                    'message' => "Your order  #".$order->hash." has been cancelled by the vendor ".$order->seller->username,
                    'image' => $order->seller->avatar,
                    'url'   => "/account/purchase-history/".$order->hash,
                    'vendor'   => ""
                 ];
        
                $order->user->notify(new Alert($details));

                switch($order->currency) {
                    case "XMR":
                       $order->user->xmr_balance = $order->user->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                    break;
                    case "BTC":
                        $order->user->btc_balance = $order->user->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                    break;
                    case "LTC":
                        $order->user->ltc_balance = $order->user->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                    break;
                }
                $order->user->save();



            } else if($order->status == 'accepted') {
                $order->status="cancelled";
                $details = [
                    'message' => "Your order  #".$order->hash." has been cancelled by the vendor ".$order->seller->username,
                    'image' => $order->seller->avatar,
                    'url'   => "/account/purchase-history/".$order->hash,
                    'vendor'   => ""
                 ];


                 switch($order->currency) {
                    case "XMR":
                       $order->user->xmr_balance = $order->user->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                    break;
                    case "BTC":
                        $order->user->btc_balance = $order->user->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                    break;
                    case "LTC":
                        $order->user->ltc_balance = $order->user->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);
                    break;
                }
                $order->user->save();
        
                $order->user->notify(new Alert($details));
            }
            break;
            case "sellernotes":
                if($request->input('notes')) {
                    $order->notes= $request->input('notes');
                    $notifications->NotifySellerNoteBuyer($order);
                 }
            break;

            default:
            return redirect()->back();
        }

        $order->save();


        return redirect(route('account.orders.show', $order))->with('message', __('validation.order_is_updated'));
    }

    public function Sign($hex,$id){

        MetaTag::set('title', __('messages.signingmultisig_title'));

        return view("account.orders.sign")->with(["hex"=>$hex,"order_id"=>$id]);
    }


    public function SignAction(Request $request){
        $this->validator($request->all())->validate();
        $multisig = new MultisigController();
        $partially_signed = $multisig->DecodeTransaction($request->input("hex"));
        $multisig_record = MultisigTransactions::where("order_id",$request->input("order_id"))->first();
        if(is_object($partially_signed) && isset($partially_signed->result['vout'])){
            $transaction_info = $multisig->GetTransaction($partially_signed->result['vin'][0]['txid']);
            $decoded_info = $multisig->DecodeTransaction($transaction_info->result['hex']);
            $scriptpubkey = "";
            $amount = 0;
            foreach ($decoded_info->result['vout'] as $transaction){
                if($transaction['scriptPubKey']['addresses']['0'] == $multisig_record->multisig_address){
                    $scriptpubkey= $transaction['scriptPubKey']['hex'];
                    $amount = $transaction['value'];
                }
            }
            $json = '[
                  {
                    "txid": "'.$partially_signed->result['vin'][0]['txid'].'",
                    "vout": '.$partially_signed->result['vin'][0]["vout"].',
                    "scriptPubKey": "'.$scriptpubkey.'", 
                    "redeemScript": "'.$multisig_record->multisig_redeem.'",
                    "amount": '.$amount.'
                  }
               ]';

            $key = $request->input("private_key");

            $signed = $multisig->SignTransaction($request->input("hex"),$json,$key);

            $send = $multisig->SendTransaction($signed->result['hex']);
            

            if($signed == "Invalid private key") {
                return redirect()->back()->with("error",__('validation.error'))->withErrors(['private_key' => ['Your custom message here.']]);
            }

           if(!$signed->result['complete']) {
            return redirect()->back()->with("error",__('validation.error'))->withErrors(['private_key' => ['Your custom message here.']]);
           }


           if($send == "Transaction already in block chain") {
            return redirect()->back()->with("error",__('validation.withdraw_problem'));
           }


            session()->put("success", __('validation.order_funds_is_sent'). " ".$send->result);

            return view("account.orders.sign")->with(["hex"=>"success :".$send->result,"order_id"=>$request->input("order_id")]);

        }else{

            session()->put("error",__('validation.error'));

            return redirect()->back()->with("error",__('validation.error'));
        }

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'hex' => 'required|string',
            'private_key' => 'required|string',
            'captcha' => ['required', new CaptchaCheck]
        ]);
    }


}

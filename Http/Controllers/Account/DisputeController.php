<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Dispute;
use App\Models\DisputeReply;
use App\Models\Order;
use App\Notifications\Alert;

use App\Http\Controllers\MultisigController;
use App\Models\MultisigAddresses;
use App\Models\MultisigTransactions;

use App\Http\Controllers\NotificationController;


use MetaTag;
use Validator;

class DisputeController extends Controller
{
    public function index() {
        MetaTag::set('title', __('messages.profile_disputes'));
        MetaTag::set('vendor_id', 15);
        MetaTag::set('id', "2");

        return view('account.disputes.dispute');
    }

    public function dispute($id) {
        $dispute = Dispute::where('id',$id)->first();

        if ($dispute == null) {
            return redirect()->back();
        }

        if (auth()->user()->id !== $dispute->seller->id && auth()->user()->id !== $dispute->buyer->id ) {
            if (auth()->user()->is_admin != 1) {
                return redirect()->back();
            }
        }

        MetaTag::set('title',  __('messages.account_dispute')."#".$id);
        MetaTag::set('vendor_id', 15);
        MetaTag::set('id', "2");


        return view('account.disputes.show')->with([
        'single_dispute'=>$dispute
      ]);
    }

    public function createDispute($id,Request $request){
        $order = Order::where('id',$id->id)->first();

        if($order == null) {
            return redirect()->back();
        }

        if(auth()->user()->id != $order->user_id) {
            return redirect()->back();
        }

        if($order->status != "shipped") {
            return redirect()->back();
        }

        $dispute = new Dispute;
        $dispute->seller_id = $order->seller_id;
        $dispute->buyer_id = $order->user_id;
        $dispute->order_id = $order->id;
        $dispute->save();

        $order->status="disputed";
        $order->save();

        $details = [
            'message' => "Dispute #".$dispute->id  ." by the order  #".$order->hash." has been created by the user ".$order->user->username. ' funds is now freezed',
            'image' => "/web/images/dispute.png",
            'url'   => "/account/orders/".$order->hash,
            'vendor'   => ""
         ];

        $order->seller->notify(new Alert($details));

        return redirect('/account/dispute/0/'.$dispute->id);

    }

    public function sentMessage($id, Request $request) {

        $dispute = Dispute::where('id',$id)->first();
        if ($dispute == null) {
            return redirect()->back();
        }

        if (auth()->user()->id !== $dispute->seller->id && auth()->user()->id !== $dispute->buyer->id ) {
            if (auth()->user()->is_admin != 1) {
                return redirect()->back();
            }
        }

        if($dispute->resolved == 1) {
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $reply = new DisputeReply;
        $reply->user_id = auth()->user()->id;
        $reply->dispute_id = $dispute->id;
        $reply->message = $request->message;
        $reply->save();


       

        if($dispute->buyer->id == auth()->user()->id) {
            $details = [
                'message' => "The buyer ".auth()->user()->username ." has sent a message by Dispute #".$dispute->id,
                'image' => "/web/images/dispute.png",
                'url'   => "/account/dispute/0/".$dispute->id,
                'vendor'   => ""
             ];

            $dispute->seller->notify(new Alert($details));
        } else {
            $details = [
                'message' => "The vendor ".auth()->user()->username ." has sent a message by Dispute #".$dispute->id,
                'image' => "/web/images/dispute.png",
                'url'   => "/account/dispute/0/".$dispute->id,
                'vendor'   => ""
             ];
            $dispute->buyer->notify(new Alert($details));
        }
       
        return redirect()->back();

    }


    public function cancel($id, Request $request) {

        $control = new MultisigController;
        $notifications = new NotificationController();



        $dispute = Dispute::where('id',$id)->first();
        if ($dispute == null) {
            return redirect()->back();
        }
        if($dispute->resolved == 1) {
            return redirect()->back();
        }

        if (auth()->user()->id == $dispute->buyer->id ) {
            $reply = new DisputeReply;
            $reply->user_id = 0;
            $reply->dispute_id = $dispute->id;
            $reply->adminreply = 1;
            $reply->message = "Buyer has cancelled the dispute which makes the order finalized. The vendor will get the funds now transfered.";
            $reply->save();

           

            $order = $dispute->order;
            $order->status = "finalized";
            $order->save();

            if($dispute->order->is_multisig == 1) {
                $multisig_record = MultisigTransactions::where('order_id',$dispute->order->id)->first();
                $multisig_sign = $control->SignMultiSigTransaction($multisig_record,$dispute->seller->address_sales);

                if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                    $notifications->notifyOrderFinalized($order->seller->id,$dispute->order->id,"multisig",$multisig_sign->result['hex']);
                }else{
                    return redirect()->back()->with('error',__('validation.error'));
                }

            } else {
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
                  $order->seller->save();

                  $details = [
                    'message' => "Dispute #".$dispute->id  ." by the order  #".$order->hash." has been cancelled by the user ".$order->user->username. ' the funds is transfered to you',
                    'image' => "/web/images/dispute.png",
                    'url'   => "/account/orders/".$order->hash,
                    'vendor'   => ""
                 ];
        
                $order->seller->notify(new Alert($details));
            }
           

          
    
            $dispute->resolved = 1;
            $dispute->winner = $dispute->seller->id;
            $dispute->save();
        } else if (auth()->user()->id == $dispute->seller->id) {
            $reply = new DisputeReply;
            $reply->user_id = 0;
            $reply->dispute_id = $dispute->id;
            $reply->adminreply = 1;
            $reply->message = "Vendor has cancelled the dispute. The buyer will get the funds now transfered.";
            $reply->save();

         
           

 
            $order = $dispute->order;
            $order->status = "cancelled";
            $order->save();

            if($dispute->order->is_multisig == 1) {
                $multisig_record = MultisigTransactions::where('order_id',$dispute->order->id)->first();
                $multisig_sign  = $control->SignMultiSigTransaction($multisig_record,$order->user->address_refunds);
                dd($multisig_sign);
                if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                    $notifications->notifyOrderCancelled($order->user->id,$dispute->order->id,"multisig",$multisig_sign->result["hex"]);
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

                $details = [
                    'message' => "Dispute #".$dispute->id  ." by the order  #".$order->hash." has been cancelled by the vendor ".$order->seller->username. ' your funds has been returned',
                    'image' => "/web/images/dispute.png",
                    'url'   => "/account/purchase-history/".$order->hash,
                    'vendor'   => ""
                 ];
                 $order->user->notify(new Alert($details));
            }
           

           
    
    
            $dispute->resolved = 1;
            $dispute->winner = $dispute->buyer->id;
            $dispute->save();
        } else {
            return redirect()->back();
        }
 
        return redirect()->back();

    }


}

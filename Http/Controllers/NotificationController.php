<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\Alert;
use MetaTag;


class NotificationController extends Controller
{
    public function index(){
        MetaTag::set('title', __('messages.notification_title'));

        return view('notifications');
    }

    public function delete($id){ 
        auth()->user()->notifications()
        ->where('id', $id)
        ->get()
        ->first()
        ->delete();

      return redirect()->back();
    }

    public function deleteAllNotifications(){ 
        auth()->user()->notifications()->delete();

      return redirect()->back();
    }


    public function markAllRead(){ 
        foreach(auth()->user()->notifications as $notification) {
                $notification->markAsRead();
        }
      return redirect()->back();
    }


    public function go($id){ 
        foreach(auth()->user()->notifications as $notification) {
            if($notification->id == $id) {
                $notification->markAsRead();
                return redirect($notification->data['url']);
            }
        }
      return redirect()->back();
    }

    public function notifyLitecoinDepositConfirmed($amount,$user){
        $details = [
              'message' => "Litecoin deposit of ".$amount ." is now confirmed. Good luck with shopping!",
              'image' => "/web/images/ltc.png",
              'url' => "/account/wallet/btc",
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }


    public function welcomeAsVendor($user){
        $details = [
              'message' => "You are now a Roman Vendor! We wish you further good luck in building your business here at Pax Romana.",
              'image' => "/web/images/icon.png",
              'url' => "/wiki/4",
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }

      public function invitedUser($user,$id){
        $details = [
              'message' => "You have been invited to a chat #".$id,
              'image' => "/web/images/icon.png",
              'url' => "/inbox/".$id,
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }

      public function FErightEnabled($user){
        $details = [
              'message' => "You'r FE rights has been enabled by the Pax Staff. Be aware any abuse will result in ban.",
              'image' => "/web/images/icon.png",
              'url' => "/wiki/4",
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }

      public function notifyMoneroDepositConfirmed($amount,$user){
        $details = [
              'message' => "Monero deposit of ".$amount ." is now confirmed. Good luck with shopping!",
              'image' => "/web/images/xmr.png",
              'url' => "/account/wallet/btc",
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }

      public function notifyBitcoinDepositConfirmed($amount,$user){
        $details = [
              'message' => "Bitcoin deposit of ".$amount ." is now confirmed. Good luck with shopping!",
              'image' => "/web/images/btc.png",
              'url' => "/account/wallet/btc",
              'vendor'   => ""
           ];
  
           $user = User::find($user);
           $user->notify(new Alert($details));
      }
      

    public function notifyBitcoinDeposit($amount,$user){
      $details = [
            'message' => "There is a new Bitcoin deposit coming through of ".$amount ."BTC",
            'image' => "/web/images/btc.png",
            'url' => "/account/wallet/btc",
            'vendor'   => ""
         ];

         $user = User::find($user);
         $user->notify(new Alert($details));
        
    }

    public function notifyLitecoinDeposit($amount,$user){
        
           $details = [
            'message' => "There is a new Litecoin deposit coming through of ".$amount ."BTC",
            'image' => "/web/images/ltc.png",
            'url' => "/account/wallet/ltc",
            'vendor'   => ""
         ];

         $user = User::find($user);
        $user->notify(new Alert($details));
    }


    public function notifyMoneroDeposit($amount,$user){
        $details = [
            'message' => "There is a new Monero deposit coming through of ".$amount ."BTC",
            'image' => "/web/images/xmr.png",
            'url' => "/account/wallet/xmr",
            'vendor'   => ""
         ];

         $user = User::find($user);
         $user->notify(new Alert($details));
        
    }



    public function notifyWelcomeUser($user){

        $details = [
            'message' => __('messages.notification_welcome_to_pr'),
            'image' => "/web/images/icon.png",
            'url'   => "/wiki",
            'vendor'   => ""
         ];

        $user = User::find($user);
        $user->notify(new Alert($details));
    }


    public function notifyTranReceived($user,$amount,$currency){

        $details = [
            'message' => "Your transaction of ".$amount." ".$currency." has been received. This is now in escrow via Multisig 2/3",
            'image' => "/web/images/icon.png",
            'url' => "",
            'vendor' => ""
        ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }

    public function notifyOrderCreate($user,$order_id){

        $details = [
            'message' => "You have placed order #".$order_id.".",
             'image' => "/web/images/icon.png",
             'url' => action("Account\PurchaseHistoryController@index"),
             'vendor' => ""
          ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }

    public function notifyOrderReceived($user,$order_id){

        $details = [
            'message' => "You have received a new order #".$order_id.".",
            'image' => "/web/images/icon.png",
            'url' => action("Account\OrdersController@index"),
            'vendor' => ""
        ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }

    public function notifyOrderAccept($user,$order_id){

        $details = [
            'message' => "Your order #".$order_id. " been accepted by the vendor." ,
            'image' => "/web/images/icon.png",
            'url' => action("Account\PurchaseHistoryController@index"),
            'vendor' => ""
        ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }

    public function notifyOrderShipped($user,$order_id){

        $details = [
            'message' => "Your order #".$order_id. " been shipped to you." ,
            'image' => "/web/images/icon.png",
            'url' => action("Account\PurchaseHistoryController@index"),
            'vendor' => ""
        ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }
    

    public function notifyOrderShippedFinalized($user,$order_id){

        $details = [
            'message' => "Your order #".$order_id. "been shipped to you. The seller has FE rigths, the order is now finalized." ,
            'image' => "/web/images/icon.png",
            'url' => action("Account\PurchaseHistoryController@index"),
            'vendor' => ""
        ];

        $user = User::find($user);

        $user->notify(new Alert($details));

    }


    public function notifyOrderCancelled($user,$order_id,$transaction="",$hex=""){

        if($transaction == "multisig"){

            $details = [
                'message' => "Multisig Order have been cancelled please click here to refund. #".$order_id.".",
                'image' => "/web/images/icon.png",
                'url' => action("Account\OrdersController@Sign",["hex"=>$hex,"id"=>$order_id]),
                'vendor' => ""
            ];

        }else{
            $details = [
                'message' => "Order cancelled and your funds have been refunded. #".$order_id.".",
                'image' => "/web/images/icon.png",
                'url' => action("Account\PurchaseHistoryController@index"),
                'vendor' => ""
            ];
        }



        $user = User::find($user);

        $user->notify(new Alert($details));

    }


    public function notifyOrderFinalized($user,$order_id,$transaction="",$hex=""){

        if($transaction == "multisig"){

            $details = [
                'message' => "Multisig Order have been finalized please click here to go to signing. #".$order_id.".",
                'image' => "/web/images/icon.png",
                'url' => action("Account\OrdersController@Sign",["hex"=>$hex,"id"=>$order_id]),
                'vendor' => ""
            ];

        }else{
            $details = [
                'message' => "Order finalized and your transaction is released. #".$order_id.".",
                'image' => "/web/images/icon.png",
                'url' => action("Account\OrdersController@finalized"),
                'vendor' => ""
            ];
        }



        $user = User::find($user);

        $user->notify(new Alert($details));

    }

    public function NotifyDisputeCreated($dispute){

        $details = [
            'message' => "Order has been disputed. #".$dispute->order_id,
            'image' => "/web/images/icon.png",
            'url' => action("Account\DisputeController@index"),
            'vendor' => ""
        ];

        $seller = User::find($dispute->seller_id);
        $buyer = User::find($dispute->buyer_id);

        $seller->notify(new Alert($details));
        $buyer->notify(new Alert($details));

    }

    public function NotifyAutoDispatchNoteBuyer($order){

        $details = [
            'message' => __('messages.account_order') ."#".$order->id . ' '. __('messages.notification_autodispatch_note'),
            'image' => "/web/images/icon.png",
            'url' => action("Account\PurchaseHistoryController@index"),
            'vendor' => ""
        ];

        $buyer = User::find($order->user->id);

        $buyer->notify(new Alert($details));
    }
    
    public function NotifySellerNoteBuyer($order){

        $details = [
            'message' => __('messages.profile_seller') . " " .$order->seller->username . " ". __('messages.notification_seller_note') . " " ."#".$order->id,
            'image' => "/web/images/icon.png",
            'url' => action("Account\PurchaseHistoryController@index"),
            'vendor' => ""
        ];

        $buyer = User::find($order->user->id);

        $buyer->notify(new Alert($details));
    }



    public function NotifyDisputeDecision($dispute,$winner){

        $details = [
            'message' => __('messages.notification_dispute_title'),
            'image' => "/web/images/icon.png",
            'url' => action("Account\DisputeController@index"),
            'vendor' => ""
        ];

        $seller = User::find($dispute->seller_id);
        $buyer = User::find($dispute->buyer_id);

        if($winner == "buyer"){
            $details['message'] = __('messages.notification_dispute_decis1');
        }else{
            $details['message'] = __('messages.notification_dispute_decis2');
        }

        $seller->notify(new Alert($details));

        $buyer->notify(new Alert($details));

    }


    public function notifyOrderCommission($referrer,$user,$amount,$currency){

        $details = [
            'message' => __('messages.notification_dispute_decis2') ." ".$amount." ".$currency,
            'image' => "/web/images/icon.png",
            'url' => action("Account\ReferralsController@index"),
            'vendor' => ""
        ];

        $referrer = User::find($referrer);

        $referrer->notify(new Alert($details));

    }


    public function notifyContinueOrder($temp_id,$user_id){

        $details = [
            'message' => "Your Multisig transaction has been received. You can complete your order here.",
            'image' => "/web/images/icon.png",
            'url' => action("CheckoutController@ContinueOrder",["temp_id"=>$temp_id]),
            'vendor' => ""
        ];

        $user = User::find($user_id);

        $user->notify(new Alert($details));

    }

}

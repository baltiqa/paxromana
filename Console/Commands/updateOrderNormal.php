<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use App\Http\Controllers\MultisigController;
use App\Http\Controllers\NotificationController;
use App\Models\MultisigAddresses;
use App\Models\MultisigTransactions;
use App\Models\Referral;
use App\Models\Currency;


class updateOrderNormal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateOrderNormal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notifications = new NotificationController();
        // $multisig = new MultisigController();


        $ordersProcess = Order::process()->get();

        $ordersAccepted = Order::accepted()->get();

        $ordersFinalized = Order::shipped()->get();


        foreach($ordersProcess as $order) {
            if(Carbon::now() > $order->auto_final) {
                $order->shipping_address = "Removed";

                $service_fee = $order->service_fee;

                if($order->is_multisig ==1 ) {
                    // $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                    // $multisig_sign = $multisig->SignMultiSigTransaction($multisig_record,$buyer->address_refunds);
                    // if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                    //     $notifications->notifyOrderCancelled($order->user_id,$order->id,"multisig",$multisig_sign->result["hex"]);
                    // }else{
                    //     return redirect()->back()->with('error',__('validation.error'));
                    // }

                    $order->status ="cancelled";
                    $order->
                    $order->save();
                } else {
                    $buyer = $order->buyer;
                    switch($order->currency) {
                        case "XMR":
                           $buyer->xmr_balance = $buyer->xmr_balance + ($order->price - $service_fee);
                        break;
                        case "BTC":
                            $buyer->btc_balance = $buyer->btc_balance + ($order->price- $service_fee);
                        break;
                        case "LTC":
                            $buyer->ltc_balance = $buyer->ltc_balance +  ($order->price- $service_fee);
                        break;
                    }
                    $buyer->save();
                    $order->status ="cancelled";
                    $order->save();
                }
            }
        }

        foreach($ordersAccepted as $order) {
            if(Carbon::now() > $order->auto_final) {
                $order->shipping_address = "Removed";
                if($order->is_multisig ==1 ) {
                    // $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                    // $multisig_sign = $multisig->SignMultiSigTransaction($multisig_record,$buyer->address_refunds);
                    // if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                    //     $notifications->notifyOrderCancelled($order->user_id,$order->id,"multisig",$multisig_sign->result["hex"]);
                    // }else{
                    //     return redirect()->back()->with('error',__('validation.error'));
                    // }

                    $order->status ="cancelled";
                    $order->save();
                } else {
                    $buyer = $order->buyer;
                    switch($order->currency) {
                        case "XMR":
                           $buyer->xmr_balance = $buyer->xmr_balance + ($order->price - $service_fee);
                        break;
                        case "BTC":
                            $buyer->btc_balance = $buyer->btc_balance + ($order->price- $service_fee);
                        break;
                        case "LTC":
                            $buyer->ltc_balance = $buyer->ltc_balance +  ($order->price- $service_fee);
                        break;
                    }
                    $buyer->save();
                    $order->status ="cancelled";
                    $order->save();
                }
            }
        }


        foreach($ordersFinalized as $order) {
            if(Carbon::now() > $order->auto_final) {
                $order->shipping_address = "Removed";
                $buyer = $order->user;
                $service_fee = $order->service_fee;
                $referral = Referral::where("user_id",$order->user_id)->first();
                 if($referral){
                  $service_fee = $service_fee - ($service_fee/0.3);
           
                  $give_referral = Referral::ReferrerCommission($order);
                  $currency_code = Currency::GetCurrency($give_referral[2]);
                  $notifications->notifyOrderCommission($give_referral[0],$buyer,$give_referral[1],$currency_code);
                 }

                if($order->is_multisig ==1 ) {
                    // $multisig_record = MultisigTransactions::where("order_id",$order->id)->first();
                    // $multisig_sign = $multisig->SignMultiSigTransaction($multisig_record,$seller->address_sales);
                    // if(isset($multisig_sign->result) && isset($multisig_sign->result['hex'])){
                    //     $notifications->notifyOrderFinalized($order->seller_id,$order->id,"multisig",$multisig_sign->result['hex']);
                    // }else{
                    //     return redirect()->back()->with('error',__('validation.error'));
                    // }

                    $order->status ="finalized";
                    $order->save();
                } else {
                    $seller = $order->seller;
                    switch($order->currency) {
                        case "XMR":
                           $seller->xmr_balance = $seller->xmr_balance + ($order->price - $service_fee);
                        break;
                        case "BTC":
                            $seller->btc_balance = $seller->btc_balance + ($order->price- $service_fee);
                        break;
                        case "LTC":
                            $seller->ltc_balance = $seller->ltc_balance +  ($order->price- $service_fee);
                        break;
                    }
                    $seller->save();
                    $order->status ="finalized";
                    $order->save();
                }
            }
        }
    }
}

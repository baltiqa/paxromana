<?php

namespace App\Http\Controllers\Account;

use App\Models\ServerCredentials;
use App\Bitcoin\Bitcoin;
use App\Models\User;
use App\Models\UserAddresses;
use App\Models\UserTransactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PGP_2FA;
use MetaTag;

class BitcoinController extends Controller
{
    protected $btc_credentials;
    protected $bitcoin;

    public function __construct(){
        $this->btc_credentials = ServerCredentials::where("currency",1)->first();
        $this->bitcoin = new Bitcoin($this->btc_credentials->username,$this->btc_credentials->password,$this->btc_credentials->host,$this->btc_credentials->port);
    }

    public function index() {
        MetaTag::set('title', "Bitcoin".' '.__('messages.wallet_title'));
        MetaTag::set('wallet_id', "1");
        MetaTag::set('id',"4");

        $user = User::find(auth()->user()->id);
        $address = $user->GetBTCAddress();
        $transactions = UserTransactions::where("user_id",$user->id)->where("currency",1)->get();
        $fee = $this->bitcoin->estimatesmartfee(6);
        $fee->result['feerate'] = floatval($fee->result['feerate'])/10;
        $fee->result['feerate'] = number_format($fee->result['feerate'], 8, '.', ",");
        return view('wallet.btc')->with(["address"=>$address,"user" => $user,"transactions" => $transactions ,"btc_fee" => $fee->result['feerate']]);
    }

    public function Withdraw(Request $request){

        $user = User::find(auth()->user()->id);

        if($user == null) {
            abort(404);
        }

        if($user->withdraw_disabled == 1) {
            return redirect()->back()->with('error', __('validation.wallet_disabled_now'));
        }

        if (strlen($request->input("btcaddress")) < 25 || strlen($request->input("btcaddress")) > 35){
            return redirect()->back()->with('error',__('validation.invalid_address'));
        }

        if ($request->input("withdrawPIN") != $user->withdrawpin){
            return redirect()->back()->with('error',__('validation.invalid_withdraw_pin'));
        }

        if($request->input("btcamount") > $user->btc_balance){
            return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }

        if($this->isSystemAddress($request->input("btcaddress"))){
            return redirect()->back()->with('error',__('validation.cant_withdraw_to_that_address'));
        }

        $amount = $request->input("btcamount");

        if($request->input("max")=="max"){
            $amount = $user->btc_balance;
        }
        $system_fee = $amount * 0.009;
        $send_amount = $amount - $system_fee;
        $fee = $this->bitcoin->estimatesmartfee(6);
        $fee = $fee->result['feerate'];
        if($request->input("btc_fee_type") == 2){
                $send_amount = $send_amount - $fee;
        }elseif ($request->input("btc_fee_type") == 3){
                $send_amount = $send_amount - ($fee*3);
        }
        if($send_amount <= 0){
            return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }
        $send_amount = number_format($send_amount,8);

        $transaction = $this->bitcoin->sendtoaddress($request->input("btcaddress"),$send_amount,"","",true);
        if(!is_object($transaction)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }
        $information = $this->bitcoin->gettransaction($transaction->result);
        if(!is_object($information)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }

        if($request->input("btc_fee_type") == 2){
             $this->bitcoin->bumpfee($transaction->result,json_decode('{"confTarget": 3,"totalFee": '.$fee.'}'));
        }elseif ($request->input("btc_fee_type") == 3){
             $this->bitcoin->bumpfee($transaction->result,json_decode('{"confTarget": 3,"totalFee": '.($fee*3).'}'));
        }

        $user->btc_balance = $user->btc_balance - $amount;
        $user->save();

        $new_transaction = new UserTransactions();
        $new_transaction->tx_id = $transaction->result;
        $new_transaction->user_id = $user->id;
        $new_transaction->address = $information->result['details'][0]['address'];
        $new_transaction->currency = 1;
        $new_transaction->amount = $information->result['details'][0]['amount'];
        $new_transaction->type = "Withdraw";
        $new_transaction->confirmations = $information->result['confirmations'];
        $new_transaction->status = "Completed";
        $new_transaction->save();

        return redirect()->back()->with('success',__('validation.payment_sent'));


    }

    public function clearHistory(Request $request){
        $transactions = UserTransactions::where("user_id",auth()->user()->id)->where("currency",1)->get();

        if(count($transactions) == 0) {
            return redirect()->back()->with('error',__('validation.no_history'));
        }

        foreach($transactions as $transaction) {
            if($transaction->status != "Completed") {
                return redirect()->back()->with('error',__('validation.not_possible_empty'));
            break;
            }
            $transaction->delete();
        }

        return redirect()->back()->with('success',__('validation.history_emptied'));

    }



    public function signBitcoinAddress() {
        MetaTag::set('title', __('messages.verifyaddress_title'));
        MetaTag::set('wallet_id',"1");
        MetaTag::set('id',"4");


        $user = User::find(auth()->user()->id);
        $address = $user->GetBTCAddress();

        if($address == null) {
            return redirect()->back();
        }

        $pgp = new PGP_2FA;
        $message =  $pgp->sign('This signature has been created by the Pax Romana PGP Public key. 

This Bitcoin address '. $address  .' belongs to the user '.auth()->user()->username.'       
        
You are now on the Pax Romana URL ' . env('WEBSITE_URL') . ' 

If this URL doesn\'t match with in the Browser  dont deposit. Sent the support a message. Verify this message before deposit!


Experience is the teacher of all things. -JC');

return view('wallet.sign.btc',compact('message'));

    }

    public function isSystemAddress($address){
        $address = UserAddresses::where("btc_address",$address)->first();
        if($address){
            return true;
        }else{
            return false;
        }
    }

}

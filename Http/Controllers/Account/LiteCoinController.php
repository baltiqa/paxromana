<?php

namespace App\Http\Controllers\Account;

use App\Models\ServerCredentials;
use App\Litecoin\Litecoin;
use App\Models\UserAddresses;
use App\Models\User;
use App\Models\UserTransactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PGP_2FA;
use MetaTag;

class LiteCoinController extends Controller
{

    protected $ltc_credentials;
    protected $litecoin;

    public function __construct(){
        $this->ltc_credentials = ServerCredentials::where("currency",2)->first();
        $this->litecoin = new Litecoin($this->ltc_credentials->username,$this->ltc_credentials->password,$this->ltc_credentials->host,$this->ltc_credentials->port);
    }

   public function index() {
        MetaTag::set('title', "Litecoin".' '.__('messages.wallet_title'));
        MetaTag::set('wallet_id',"2");
        MetaTag::set('id',"4");

        $user = User::find(auth()->user()->id);
        $address = $user->GetLTCAddress();
        $transactions = UserTransactions::where("user_id",$user->id)->where("currency",2)->get();
        $fee = $this->litecoin->estimatesmartfee(6);
        $fee->result['feerate'] = number_format($fee->result['feerate'], 8, '.', ",");
        return view('wallet.ltc')->with(["address"=>$address,"user" => $user,"transactions" => $transactions, "ltc_fee" => $fee->result['feerate']]);
    }

    public function Withdraw(Request $request){

        $user = User::find(auth()->user()->id);

        if($user == null) {
            abort(404);
        }

        if($user->withdraw_disabled == 1) {
            return redirect()->back()->with('error', __('validation.wallet_disabled_now'));
        }

        if (strlen($request->input("ltcaddress")) < 30 || strlen($request->input("ltcaddress")) > 45){
            return redirect()->back()->with('error',__('validation.invalid_address'));
        }

        if ($request->input("withdrawPIN") != $user->withdrawpin){
            return redirect()->back()->with('error',__('validation.invalid_withdraw_pin'));
        }

        if($request->input("ltcamount") > $user->ltc_balance){
            return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }

        if($this->isSystemAddress($request->input("ltcaddress"))){
            return redirect()->back()->with('error',__('validation.cant_withdraw_to_that_address'));
        }

        $amount = $request->input("ltcamount");
        if($request->input("max")=="max"){
            $amount = $user->ltc_balance;
        }
        $system_fee = $amount * 0.009;
        $send_amount = $amount - $system_fee;
        $fee = $this->litecoin->estimatesmartfee(6);
        $fee = $fee->result['feerate'];
        if($request->input("ltc_fee_type") == 2){
            $send_amount = $send_amount - $fee;
        }elseif ($request->input("ltc_fee_type") == 3){
            $send_amount = $send_amount - ($fee*3);
        }
        if($send_amount <= 0){
            return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }
        $send_amount = number_format($send_amount,8);
        $transaction = $this->litecoin->sendtoaddress($request->input("ltcaddress"),$send_amount,"","",true);
        if(!is_object($transaction)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }
        $information = $this->litecoin->gettransaction($transaction->result);
        if(!is_object($information)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }

        if($request->input("ltc_fee_type") == 2){
            $this->litecoin->bumpfee($transaction->result,json_decode('{"confTarget": 3,"totalFee": '.$fee.'}'));
        }elseif ($request->input("ltc_fee_type") == 3){
            $this->litecoin->bumpfee($transaction->result,json_decode('{"confTarget": 3,"totalFee": '.($fee*3).'}'));
        }

        $user->ltc_balance = $user->ltc_balance - $amount;
        $user->save();

        $new_transaction = new UserTransactions();
        $new_transaction->tx_id = $transaction->result;
        $new_transaction->user_id = $user->id;
        $new_transaction->address = $information->result['details'][0]['address'];
        $new_transaction->currency = 2;
        $new_transaction->amount = $information->result['details'][0]['amount'];
        $new_transaction->type = "Withdraw";
        $new_transaction->confirmations = $information->result['confirmations'];
        $new_transaction->status = "Completed";
        $new_transaction->save();

        return redirect()->back()->with('success',__('validation.payment_sent'));

    }

    public function clearHistory(Request $request){
        $transactions = UserTransactions::where("user_id",auth()->user()->id)->where("currency",2)->get();

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

    public function isSystemAddress($address){
        $address = UserAddresses::where("ltc_address",$address)->first();
        if($address){
            return true;
        }else{
            return false;
        }
    }

    public function signLiteCoinAddress() {
        MetaTag::set('title', __('messages.verifyaddress_title'));
        MetaTag::set('wallet_id',"2");
        MetaTag::set('id',"4");


        $user = User::find(auth()->user()->id);
        $address = $user->GetLTCAddress();

        if($address == null) {
            return redirect()->back();
        }

        $pgp = new PGP_2FA;
        $message =  $pgp->sign('This signature has been created by the Pax Romana PGP Public key. 

This LiteCoin address '. $address  .' belongs to the user '.auth()->user()->username.'       
        
You are now on the Pax Romana URL ' . env('WEBSITE_URL') . ' 

If this URL doesn\'t match with in the Browser  dont deposit. Sent the support a message. Verify this message before deposit!


Experience is the teacher of all things. -JC');

return view('wallet.sign.ltc',compact('message'));
    }

}

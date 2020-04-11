<?php

namespace App\Http\Controllers\Account;

use App\Models\ServerCredentials;
use App\Monero\Monero;
use App\Models\UserAddresses;
use App\Models\User;
use App\Models\UserTransactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PGP_2FA;
use MetaTag;


class MoneroController extends Controller
{

    protected $xmr_credentials;
    protected $monero;

    public function __construct(){
        $this->xmr_credentials = ServerCredentials::where("currency",3)->first();
        $this->monero = new Monero($this->xmr_credentials->username,$this->xmr_credentials->password,$this->xmr_credentials->host,$this->xmr_credentials->port);
    }

    public function index() {
        MetaTag::set('title', "Monero".' '.__('messages.wallet_title'));
        MetaTag::set('wallet_id', "3");
        MetaTag::set('id',"4");

        $user = User::find(auth()->user()->id);
        $address = $user->GetXMRAddress();
        $transactions = UserTransactions::where("user_id",$user->id)->where("currency",3)->get();
        $fee = $this->monero->get_fee_estimate();
        $fee->result['fee'] = intval($fee->result['fee'])/pow(10,11);
        $fee_normal = number_format($fee->result['fee'], 12, '.', "");
        return view('wallet.xmr')->with(["address"=>$address,"user" => $user,"transactions" => $transactions,"xmr_fee_normal" =>$fee_normal]);
    }

    public function Withdraw(Request $request){

        $user = User::find(auth()->user()->id);

        if($user == null) {
            abort(404);
        }

        if($user->withdraw_disabled == 1) {
            return redirect()->back()->with('error', __('validation.wallet_disabled_now'));
        }

        if (strlen($request->input("xmraddress")) < 95 || strlen($request->input("xmraddress")) > 106){
            return redirect()->back()->with('error',__('validation.invalid_address'));
        }

        if ($request->input("withdrawPIN") != $user->withdrawpin){
            return redirect()->back()->with('error',__('validation.invalid_withdraw_pin'));
        }

	if($request->input("xmramount") > $user->xmr_balance){
           return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }

        if($this->isSystemAddress($request->input("xmraddress"))){
            return redirect()->back()->with('error',__('validation.cant_withdraw_to_that_address'));
        }

        $amount = $request->input("xmramount");
        if($request->input("max")=="max"){
            $amount = $user->xmr_balance;
        }
	$amount = $amount * pow(10,11);
	$system_fee = $amount * 0.009;
	$send_amount = $amount - $system_fee;
	if($send_amount <= 0){
            return redirect()->back()->with('error',__('validation.not_enough_balance'));
        }
        $transaction = $this->monero->transfer(["destinations"=>array(0 => array("amount"=>$send_amount,"address"=>$request->input("xmraddress")))]);
        if(!is_object($transaction)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }
        $information = $this->monero->get_transfer_by_txid(array("txid" =>$transaction->result["tx_hash"]));
        if(!is_object($information)){
            return redirect()->back()->with('error',__('validation.withdraw_problem'));
        }

        $user->xmr_balance = $user->xmr_balance - $amount;
        $user->save();

        $new_transaction = new UserTransactions();
        $new_transaction->tx_id = $transaction->result['tx_hash'];
        $new_transaction->user_id = $user->id;
        $new_transaction->address = $information->result['transfer']['address'];
        $new_transaction->currency = 3;
        $new_transaction->amount = number_format($information->result['transfer']['amount']/pow(10,11),2,".",",");
        $new_transaction->type = "Withdraw";
        $new_transaction->confirmations = $information->result['transfer']['confirmations'];
        $new_transaction->status = "Completed";
        $new_transaction->save();

        return redirect()->back()->with('success',__('validation.payment_sent'));

    }

    public function clearHistory(Request $request){
        $transactions = UserTransactions::where("user_id",auth()->user()->id)->where("currency",3)->get();
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
        $address = UserAddresses::where("xmr_address",$address)->first();
        if($address){
            return true;
        }else{
            return false;
        }
    }

    public function signMoneroAddress() {

        MetaTag::set('title', __('messages.verifyaddress_title'));
        MetaTag::set('wallet_id', "3");
        MetaTag::set('id',"4");


        $user = User::find(auth()->user()->id);
        $address = $user->GetXMRAddress();

        if($address == null) {
            return redirect()->back();
        }

        $pgp = new PGP_2FA;
        $message =  $pgp->sign('This signature has been created by the Pax Romana PGP Public key. 

This Monero address '. $address  .' belongs to the user '.auth()->user()->username.'       
        
You are now on the Pax Romana URL ' . env('WEBSITE_URL') . ' 

If this URL doesn\'t match with in the Browser  dont deposit. Sent the support a message. Verify this message before deposit!


Experience is the teacher of all things. -JC');
        

        return view('wallet.sign.xmr',compact('message'));
    }


}

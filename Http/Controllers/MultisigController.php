<?php
/**
 * Created by PhpStorm.
 * User: fareed.nema94@gmail.com
 * Date: 1/19/2020
 * Time: 4:12 PM
 */

namespace App\Http\Controllers;

use App\Bitcoin\Bitcoin;
use App\Models\MultisigAddresses;
use App\Models\MultisigTransactions;
use App\Models\Referral;
use App\Models\ServerCredentials;
use App\Models\User;
class MultisigController extends Controller
{

  private $bitcoin;

  private $credentials;

  private $user;

  public function __construct(){

      $this->user = User::find(auth()->user()->id);
      $this->credentials = ServerCredentials::where("currency",1)->first();
      $this->bitcoin = new Bitcoin($this->credentials->username,$this->credentials->password,$this->credentials->host,$this->credentials->port);

  }

  public function CreateAddress($multisig="",$amount=null){
      $address = $this->bitcoin->getnewaddress();
      $address_info = $this->bitcoin->getaddressinfo($address->result);
      if($multisig == "multisig"){
          $multisig_record = new MultisigTransactions();
          $multisig_record->multisig_address = "pending";
          $multisig_record->multisig_redeem = "pending";
          $multisig_record->multisig_amount = number_format($amount,8);
          $multisig_record->listing_id = null;
          $multisig_record->buyer_id = null;
          $multisig_record->seller_id = null;
          $multisig_record->save();
          $address_info->result['multisig_id'] = $multisig_record->id;
          $addresses_record = new MultisigAddresses();
          $addresses_record->multisig_id = $multisig_record->id;
          $addresses_record->address_1 = null; // seller Key
          $addresses_record->address_2 = $address->result; // Website address
          $addresses_record->address_3 = null; // buyer Key
          $addresses_record->save();
      }

      return (object) $address_info->result;
  }

  public function ValidateAddress($address){
      $result = $this->bitcoin->getaddressinfo($address);
      return $result;
  }

  public function ValidateKey($key){
      $key_1= $key;
      $key_2= $this->CreateAddress();
      $key_3= $this->CreateAddress();
      $addresses_array = array($key_1,$key_2->pubkey,$key_3->pubkey);
      $multisig_address = $this->bitcoin->addmultisigaddress(2,$addresses_array);
      return $multisig_address;
  }

  public function CreateMultiSig($listing,$buyer,$address_2){

      $addresses_record =  MultisigAddresses::where("address_2",$address_2)->first();
      $multisig_record = MultisigTransactions::where("id",$addresses_record->multisig_id)->first();

      $seller = User::find($listing->user_id);


      $key_1 = $seller->multisig_key_pub;
      $key_2 = $address_2;
      $key_3 = $buyer->multisig_key_pub;
      $addresses_array = array($key_1,$key_2,$key_3);

      $multisig_address = $this->bitcoin->addmultisigaddress(2,$addresses_array);

      if(!is_object($multisig_address) || !isset($multisig_address->result['address'])){
          return false;
      }

      $multisig_record->multisig_address = $multisig_address->result['address'];
      $multisig_record->multisig_redeem = $multisig_address->result['redeemScript'];
      $multisig_record->listing_id = $listing->id;
      $multisig_record->buyer_id = $buyer->id;
      $multisig_record->seller_id = $listing->user_id;

      $store_fee = $seller->commission/100;
      $store_fee = number_format(($multisig_record->multisig_amount * $store_fee),8);
      $multisig_record->multisig_amount = $multisig_record->multisig_amount - $store_fee;
      $multisig_record->multisig_amount = number_format($multisig_record->multisig_amount,8);
      $addresses_record->address_1 = $key_1; // seller Key
      $addresses_record->address_3 = $key_3; // buyer Key
      $addresses_record->save();

      $multisig_send = $this->bitcoin->sendtoaddress($multisig_address->result['address'],$multisig_record->multisig_amount,"","",true);

      if(!isset($multisig_send->result)){
          return false;
      }

      $multisig_record->multisig_transaction = $multisig_send->result;
      $multisig_record->save();

      $referral = Referral::where("user_id",$buyer->id);

      $service_fee = $store_fee;

      if($referral){
          $service_fee = $store_fee - ($store_fee/floatval(setting("referral_commission"))/100);
          $service_fee = number_format($service_fee,8);
      }

      $this->bitcoin->sendtoaddress(setting("admin_wallet_btc"),$service_fee,"","",true);

      return $multisig_address;

  }

  public function CheckMultiSigAmount($address,$confirmations = 0){

      $addresses_record =  MultisigAddresses::where("address_2",$address)->first();
      $multisig_record = MultisigTransactions::where("id",$addresses_record->multisig_id)->first();
      $address = $addresses_record->address_2;
      $amount = $this->bitcoin->getreceivedbyaddress($address,$confirmations);
      if($amount && $amount->result >= $multisig_record->multisig_amount){
          return true;
      }else{
          return false;
      }

  }

  public function GetTransaction($tx_id){
      $transaction_info = $this->bitcoin->gettransaction($tx_id);
      return $transaction_info;
  }

  public function GetMultiSigTxid($address){
      $record = MultisigTransactions::where("multisig_address",$address)->first();
      $transaction_info = $this->GetTransaction($record->multisig_transaction);
      return $transaction_info;
  }

  public function GetScriptPubKey($transaction_info){
      $raw_transaction = $this->bitcoin->decoderawtransaction($transaction_info->result['hex']);
      $transaction_vout_key = array_search($transaction_info->result['details'][0]['amount'],array_column($raw_transaction->result['vout'],'value'));
      $transaction_scriptpubkey = $raw_transaction->result['vout'][$transaction_vout_key]['scriptPubKey']['hex'];
      return $transaction_scriptpubkey;
  }

  public function GetPrivateKey($address){
      $private_key = $this->bitcoin->dumpprivkey($address);
      return $private_key->result;
  }

  public function GetMultiSigAddress($address_id){
      $addresses = new MultisigAddresses();
      $multisig_addresses = $addresses->where("multisig_id",$address_id)->first();
      return $multisig_addresses->address_2;
  }

  public function GetMultiSigPrivKey($addresses_array){
      $privkey = $this->GetPrivateKey($addresses_array);
      return $privkey;
  }

  public function GetTransactionFee(){
      $fee = $this->bitcoin->estimatesmartfee(6);
      $fee->result['feerate'] = floatval($fee->result['feerate'])/5;
      return number_format($fee->result['feerate'],8);
  }

  public function CreateRawTransaction($txid,$vout,$address,$amount){
        $json = '[
                      {
                        "txid": "'.$txid.'",
                        "vout": '.$vout.'
                      }
                   ]';
        $address = '{
                      "'.$address.'": '.$amount.'
                    }';
        $hex = $this->bitcoin->createrawtransaction(json_decode($json),json_decode($address));
        return $hex->result;
  }

  public function SignTransaction($hex,$json,$key){
      $private_key = '["'.$key.'"]';
      $result = $this->bitcoin->signrawtransactionwithkey($hex,json_decode($private_key),json_decode($json));
      return $result;
  }

  public function DecodeTransaction($hex){
      $result = $this->bitcoin->decoderawtransaction($hex);
      return $result;
  }

  public function SendTransaction($hex){
      $result = $this->bitcoin->sendrawtransaction($hex);
      return $result;
  }

  /*
   * Multisig transaction value will be negative since it is sent from the same wallet
   * In case of errors or misunderstanding you can contact me
   * */

  public function SignMultiSigTransaction($address,$receiver_address){
      $transaction_info = $this->GetMultiSigTxid($address->multisig_address);
      $fee = $this->GetTransactionFee();
      $receive_amount = number_format($transaction_info->result['details'][0]['amount']*(-1),8) - floatval($fee);
      $rawtransaction = $this->CreateRawTransaction($transaction_info->result['txid'],$transaction_info->result['details'][0]['vout'],$receiver_address,$receive_amount);
      $scriptpubkey = $this->GetScriptPubKey($transaction_info);
      $multisig_redeem = $address->multisig_redeem;
      $website_address = $this->GetMultiSigAddress($address->id);
      $private_key = $this->GetMultiSigPrivKey($website_address);
      $json = '[
                  {
                    "txid": "'.$transaction_info->result['txid'].'",
                    "vout": '.$transaction_info->result['details'][0]['vout'].',
                    "scriptPubKey": "'.$scriptpubkey.'", 
                    "redeemScript": "'.$multisig_redeem.'",
                    "amount": '.($transaction_info->result['details'][0]['amount']*(-1)).'
                  }
               ]';
      $partially_signed = $this->SignTransaction($rawtransaction,$json,$private_key);
      return $partially_signed;
  }

}

<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyLog;
use App\Models\Order;
use App\Models\Refferal;
use App\Models\ServerCredentials;
use App\Bitcoin\Bitcoin;
use App\Litecoin\Litecoin;
use App\Monero\Monero;



use Kris\LaravelFormBuilder\FormBuilder;

class BuyerLogController extends Controller
{

    private $bitcoin;
    private $monero;
    private $litecoin;

    private $btc_credentials;
    private $ltc_credentials;
    private $xmr_credentials;


    public function __construct(){
      $this->btc_credentials = ServerCredentials::where("currency",1)->first();
      $this->ltc_credentials = ServerCredentials::where("currency",2)->first();
      $this->xmr_credentials = ServerCredentials::where("currency",3)->first();

      $this->bitcoin = new Bitcoin($this->btc_credentials->username,$this->btc_credentials->password,$this->btc_credentials->host,$this->btc_credentials->port);
      $this->litecoin = new Litecoin($this->ltc_credentials->username,$this->ltc_credentials->password,$this->ltc_credentials->host,$this->ltc_credentials->port);
      $this->monero = new Monero($this->xmr_credentials->username,$this->xmr_credentials->password,$this->xmr_credentials->host,$this->xmr_credentials->port);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $data['logs'] = BuyLog::orderBy('created_at', 'DESC')->get();
        $data['btc_amount'] = number_format(BuyLog::where('paid','0')->where('currency','BTC')->sum('amount'),6);
        $data['ltc_amount'] = number_format(BuyLog::where('paid','0')->where('currency','LTC')->sum('amount'),6);
        $data['xmr_amount'] = number_format(BuyLog::where('paid','0')->where('currency','XMR')->sum('amount'),6);


        return view('panel::buyerlogs.index', $data);
    }


    public function withdrawBTC($amount,$state) {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }
     

        $transaction = $this->bitcoin->sendtoaddress(setting("admin_wallet_btc"),$amount,"","",true);

        if(!is_object($transaction)){
            return redirect()->back()->with('message','Error sending the transaction please contact support.');
        }

        if($state == 1) {
            BuyLog::where('currency', 'BTC')->where('paid',0)->update(['paid'=>1]);
        } else {
            Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('currency', 'BTC')->where('is_paid',0)->update(['is_paid'=>1]);
        }
      
        
        return redirect()->back()->with('message','Succesfully paid out');
    }

    public function withdrawLTC($amount,$state) {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $transaction = $this->litecoin->sendtoaddress(setting("admin_wallet_ltc"),$amount,"","",true);

        if(!is_object($transaction)){
            return redirect()->back()->with('message','Error sending the transaction please contact support.');
        }

        if($state == 1) {
            BuyLog::where('currency', 'LTC')->where('paid',0)->update(['paid'=>1]);
        } else {
            Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('currency', 'LTC')->where('is_paid',0)->update(['is_paid'=>1]);
        }

        return redirect()->back()->with('message','Succesfully paid out');
    }

    public function withdrawXMR($amount,$state) {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $transaction = $this->monero->transfer(["destinations"=>array(0 => array("amount"=>$amount,"address"=>setting("admin_wallet_xmr")))]);

        if(!is_object($transaction)){
            return redirect()->back()->with('message','Error sending the transaction please contact support.');
        }

        if($state == 1) {
            BuyLog::where('currency', 'XMR')->where('paid',0)->update(['paid'=>1]);
        } else {
            Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('currency', 'XMR')->where('is_paid',0)->update(['is_paid'=>1]);
        }

        return redirect()->back()->with('message','Succesfully paid out');
    }


    public function regularEscrow()
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $data['orders'] = Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->orderBy('created_at', 'DESC')->get();
        $data['btc_amount'] =  number_format(Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('is_paid',0)->where('currency','BTC')->sum('service_fee'),6);
        $data['ltc_amount'] =  number_format(Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('is_paid',0)->where('currency','LTC')->sum('service_fee'),6);
        $data['xmr_amount'] =  number_format(Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',0)->where('is_paid',0)->where('currency','XMR')->sum('service_fee'),6);

        return view('panel::buyerlogs.regularescrow', $data);
    }

    public function multisigEscrow()
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $data['orders'] = Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',1)->orderBy('created_at', 'DESC')->get();
        $data['btc_amount'] =  Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',1)->where('currency','BTC')->sum('service_fee');
        $data['ltc_amount'] =  Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',1)->where('currency','LTC')->sum('service_fee');
        $data['xmr_amount'] =  Order::whereIn('status',['finalized','cancelled'])->where('is_multisig',1)->where('currency','XMR')->sum('service_fee');

        return view('panel::buyerlogs.multisigescrow', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, FormBuilder $formBuilder)
    {
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
       

        return redirect()->back()->with('message','Successfull saved');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($market)
    {


        return redirect()->back()->with('message','Successfull saved');
    }
}


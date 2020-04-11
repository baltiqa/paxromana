<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dispute;
use App\Models\DisputeReply;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\MultisigController;
use App\Http\Controllers\NotificationController;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Notifications\Alert;
use App\Models\MultisigAddresses;
use App\Models\MultisigTransactions;
use App\Models\CurrencyRates;
use Carbon\Carbon;

class DisputeController extends Controller
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


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        if(auth()->user()->permiss()[0]['dispute'] != null) {
            if(auth()->user()->permiss()[0]['dispute'] == "no") {
                abort(404);
            }
        }

        $data = [];

        $disputes = Dispute::orderBy('created_at', 'desc');
        $data['disputes'] = $disputes->paginate(10);

        return view('panel::disputes.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        dd(5);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {

        if(auth()->user()->permiss()[0]['dispute'] != null) {
            if(auth()->user()->permiss()[0]['dispute'] == "no") {
                abort(404);
            }
        }

        $data = [];

        $dispute = Dispute::find($id);


        $currency = auth()->user()->currency;
        $buyerxmr = $dispute->buyer->getMoneroTotalSpends();
        $buyerltc = $dispute->buyer->getLitecoinTotalSpends();
        $buyerbtc = $dispute->buyer->getBitcoinTotalSpends();

        $buyerTotalSales = ($buyerxmr * $this->xmr_rates->$currency) + ($buyerltc * $this->ltc_rates->$currency) + ($buyerbtc * $this->btc_rates->$currency);

        $sellerxmr = $dispute->seller->getMoneroTotalSales();
        $sellerltc = $dispute->seller->getLitecoinTotalSales();
        $sellerbtc = $dispute->seller->getBitcoinTotalSales();

        $sellerTotalSales = ($sellerxmr * $this->xmr_rates->$currency) + ($sellerltc * $this->ltc_rates->$currency) + ($sellerbtc * $this->btc_rates->$currency);

        $data['sale_total_seller'] = $sellerTotalSales;
        $data['sale_total_buyer'] = $buyerTotalSales;

        
        $data['dispute'] = $dispute;
        $form = $formBuilder->create('Modules\Panel\Forms\DisputeForm', [
            'method' => 'PUT',
            'url' => route('panel.disputes.update', $dispute),
            'model' => $dispute
        ]);
        $data['form'] = $form;
        

        return view('panel::disputes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        if(auth()->user()->permiss()[0]['dispute'] != null) {
            if(auth()->user()->permiss()[0]['dispute'] == "no") {
                abort(404);
            }
        }


        $control = new MultisigController;
        $notifications = new NotificationController();

        $dispute = Dispute::findOrFail($id);
        
        if($request->input('moderator_message')) {
            $newReply = new DisputeReply;
            $newReply->user_id = 0;
            $newReply->dispute_id = $dispute->id;
            $newReply->message = $request->input('moderator_message');
            $newReply->adminreply = auth()->user()->id;
            $newReply->save();

            $details = [
                'message' => "The Pax Romana Moderator has responded by the dispute #".$dispute->id,
                'image' => "/web/images/dispute.png",
                'url'   => "/account/dispute/0/".$dispute->id,
                'vendor'   => ""
             ];
    
            $dispute->buyer->notify(new Alert($details));
            $dispute->seller->notify(new Alert($details));

            return redirect()->route('panel.disputes.edit',$dispute->id)->with('message', 'The message has been added.');
        }
        if($dispute->resolved == 0) {
            switch($request->input('decision')) {
                case "vendor":
                    $reply = new DisputeReply;
                    $reply->user_id = 0;
                    $reply->dispute_id = $dispute->id;
                    $reply->adminreply = auth()->user()->id;

                    $reply->message = "Vendor has won the dispute. Order will be finalized thus the vendor will get the funds now transfered.  If the order is Multisig 2/3, you need the redeem the funds yourself.";
                    $reply->save();
            
                    $order = $dispute->order;
                    $order->status = "finalized";
                    $order->save();
            
                    $dispute->resolved = 1;
                    $dispute->closed_by = auth()->user()->id;
                    $dispute->winner = $dispute->seller->id;
                    $dispute->save();

                    $seller = $dispute->seller;

                    if($dispute->order->is_multisig == 1) {
                        $multisig = new MultisigController();
                        $multisig_record = MultisigTransactions::where("order_id",$dispute->order_id)->first();

                        $multisig_release = $multisig->SignMultiSigTransaction($multisig_record,$dispute->seller->address_sales);

                        if(isset($multisig_release->result) && !isset($multisig_release->error)){
                            $notifications->notifyOrderFinalized($dispute->seller_id,$dispute->order_id,"multisig",$multisig_sign->result['hex']);
                        }else{
                            return redirect()->back()->with('error','Error during releasing the transaction , please contact support.');
                        }
                    } else {
                        switch($order->currency) {
                            case "XMR":
                               $seller->xmr_balance = $seller->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                            case "BTC":
                                $seller->btc_balance = $seller->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                            case "LTC":
                                $seller->ltc_balance = $seller->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                        }
                        $seller->save();
                    }
                    

                    $details = [
                        'message' => "The Roman Moderator has finalized  Dispute #".$dispute->id . ".",
                        'image' => "/web/images/dispute.png",
                        'url'   => "/account/dispute/0/".$dispute->id,
                        'vendor'   => ""
                     ];
            
                    $dispute->buyer->notify(new Alert($details));
                    $dispute->seller->notify(new Alert($details));
                 
    
                    return redirect()->back()->with('message', 'The dispute has been updated. Vendor has won the dispute');
                case "buyer":
                    $reply = new DisputeReply;
                    $reply->user_id = 0;
                    $reply->dispute_id = $dispute->id;
                    $reply->adminreply = auth()->user()->id;
                    $reply->message = "Buyer has won the dispute. Order will be cancelled now thus the buyer will get the funds now transfered. If the order is Multisig 2/3, you need the redeem the funds yourself. ";
                    $reply->save();
            
                    $order = $dispute->order;
                    $order->status = "cancelled";
                    $order->save();

                    $buyer = $dispute->buyer;

                    if($dispute->order->is_multisig == 1) {
                        $multisig = new MultisigController();
                        $multisig_record = MultisigTransactions::where("order_id",$dispute->order_id)->first();
                        $multisig_release = $multisig->SignMultiSigTransaction($multisig_record,$dispute->buyer->address_refunds);
                        if(isset($multisig_release->result) && !isset($multisig_release->error)){
                            $notifications->notifyOrderCancelled($dispute->buyer_id,$dispute->order_id,"multisig",$multisig_release->result["hex"]);
                        }else{
                            return redirect()->back()->with('error','Error during releasing the transaction , please contact support.');
                        }
                    } else {
                        switch($order->currency) {
                            case "XMR":
                               $buyer->xmr_balance = $buyer->xmr_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                            case "BTC":
                                $buyer->btc_balance = $buyer->btc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                            case "LTC":
                                $buyer->ltc_balance = $buyer->ltc_balance + (($order->price + $order->shipping_fee) - $order->service_fee);     
                            break;
                        }
                        $buyer->save();
                    }

                    $dispute->resolved = 1;
                    $dispute->winner = $dispute->buyer->id;
                    $dispute->closed_by = auth()->user()->id;
                    $dispute->save();

                    $details = [
                        'message' => "The Pax Romana Staff has cancelled  Dispute #".$dispute->id . " the funds are now transfered to the buyer. If the order is Multisig 2/3, you need the redeem the funds yourself.",
                        'image' => "/web/images/dispute.png",
                        'url'   => "/account/dispute/0/".$dispute->id,
                        'vendor'   => ""
                     ];
            
                    $dispute->buyer->notify(new Alert($details));
                    $dispute->seller->notify(new Alert($details));
    
                    return redirect()->back()->with('message', 'The dispute has been updated. Buyer has won the dispute');
            }
        } else {
            return redirect()->route('panel.disputes.index')->with('message', 'No update');
        }
        return redirect()->back()->with('message', 'No update');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

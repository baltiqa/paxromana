<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Order;
use App\Models\User;
use App\Models\MultisigTransactions;
use App\Models\UserTransactions;
use App\Models\ReportedListing;
use App\Bitcoin\Bitcoin;
use App\Litecoin\Litecoin;
use App\Models\ServerCredentials;
use App\Monero\Monero;
use App\Models\Tickets;
use App\Models\Dispute;
use App\Models\BuyLog;
use App\Models\ErrorLog;
use Carbon\Carbon;
use DB;
use Setting;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    protected $bitcoin;
    protected $litecoin;
    protected $monero;

    public function __construct(){
        $btc_credentials = ServerCredentials::where("currency",1)->first();
        $ltc_credentials = ServerCredentials::where("currency",2)->first();
        $xmr_credentials = ServerCredentials::where("currency",3)->first();
        $this->bitcoin = new Bitcoin($btc_credentials->username,$btc_credentials->password,$btc_credentials->host,$btc_credentials->port);
        $this->litecoin = new Litecoin($ltc_credentials->username,$ltc_credentials->password,$ltc_credentials->host,$ltc_credentials->port);
        $this->monero = new Monero($xmr_credentials->username,$xmr_credentials->password,$xmr_credentials->host,$xmr_credentials->port);
    }

    public function infoPage(){
        return view('panel::pageinfo');
    }

    public function submitIdea(Request $request){

        Setting::set('ideas', $request->input('romanaideas'));
        Setting::save();

        return redirect()->back()->with('message', 'Succesfull updated, thanks for giving your thought!');
    }

    public function index(Request $request)
    {
        if(auth()->user()->is_admin != 1) {
            return redirect('panel');
        }

        $data['users_today'] = User::where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        $data['users_total'] = User::count();
        $data['vendors_total'] = User::where('trader_type','individual')->count();
        $data['orders_today'] = Order::where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        $data['total_orders'] = Order::count();
        $data['total_listings'] = Listing::count();

        $data['monero_escrow'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','XMR')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['monero_escrow_lastday'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','XMR')->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['monero_released_escrow_lastday'] = Order::where('status','finalized')->where('currency','XMR')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['monero_fee_received'] = Order::where('status','finalized')->where('currency','XMR')->sum("service_fee");

        $data['bitcoin_escrow'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','BTC')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['bitcoin_escrow_lastday'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','BTC')->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['bitcoin_released_escrow_lastday'] = Order::where('status','finalized')->where('currency','BTC')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['bitcoin_fee_received'] = Order::where('status','finalized')->where('currency','BTC')->sum("service_fee");


        $data['litecoin_escrow'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','LTC')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['litecoin_escrow_lastday'] = Order::whereNotIn('status',['finalized','cancelled'])->where('currency','LTC')->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['litecoin_released_escrow_lastday'] = Order::where('status','finalized')->where('currency','LTC')->select(DB::raw('sum(price+shipping_fee) AS total'))->first();
        $data['litecoin_fee_received'] = Order::where('status','finalized')->where('currency','LTC')->sum("service_fee");

        $data['multisigtransactions'] = MultisigTransactions::All();
        $data['transactions'] = UserTransactions::All();
        $data['buylogs'] = BuyLog::All();
        $data['errorlogs'] = ErrorLog::All();


        // $data['ltc_info'] =  $this->litecoin->getblockchaininfo();
        // $data['btc_info'] = $this->bitcoin->getblockchaininfo();
        // $data['xmr_info'] = $this->monero->get_info();

        $data['btc_balance'] = $this->bitcoin->getbalance();
        $data['ltc_balance'] = $this->litecoin->getbalance();
        $data['xmr_balance'] = $this->monero->getbalance();


        // $data['btc_transaction'] = $this->bitcoin->listunspent(0,5);
        // $data['ltc_transaction'] = $this->litecoin->listunspent(0,5);
        // $data['xmr_transaction'] = $this->monero->get_transfers();


        return view('panel::index', $data);
    }


    public function moderatorDashboard() {

        $data['open_disputed_today'] = Dispute::where('resolved',0)->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        $data['closed_disputed_today'] = Dispute::where('resolved',1)->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();

        $data['open_tickets_today'] = Tickets::where('status',"Opened")->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        $data['closed_tickets_today'] = Tickets::where('status',"Closed")->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        
        $data['open_reports_today'] = ReportedListing::where('active',1)->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();
        $data['closed_reports_today'] = ReportedListing::where('active',0)->where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->count();

        $data['open_disputed'] = Dispute::where('resolved',0)->count();
        $data['closed_disputed'] = Dispute::where('resolved',1)->count();

        $data['open_tickets'] = Tickets::where('status',"Opened")->count();
        $data['closed_tickets'] = Tickets::where('status',"Closed")->count();
        
        $data['open_reports'] = ReportedListing::where('active',1)->count();
        $data['closed_reports'] = ReportedListing::where('active',0)->count();


        $data['tickets'] = Tickets::where('created_at','>=', date('Y-m-d H:i:s',strtotime('-123 days')))->orderBy('created_at','desc')->get();
        $data['disputes'] = Dispute::where('created_at','>=', date('Y-m-d H:i:s',strtotime('-1 days')))->orderBy('created_at','desc')->get();
        $data['reports'] = ReportedListing::where('created_at','>=', date('Y-m-d H:i:s',strtotime('-123 days')))->orderBy('created_at','desc')->get();

        return view('panel::mod_index',$data);
    }

   


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('panel::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('panel::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

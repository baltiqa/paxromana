<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MetaTag;



class WalletController extends Controller
{
    public function index(){
        MetaTag::set('title', __('messages.wallet_title'));
        MetaTag::set('id', "4");

        return view('wallet.index');
    }
}

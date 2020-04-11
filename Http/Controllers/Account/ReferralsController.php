<?php


namespace App\Http\Controllers\Account;


use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use MetaTag;
class ReferralsController extends Controller
{

    public function index(){

        MetaTag::set('title', __('messages.profile_ref_title'));
        MetaTag::set('id', "21");

        $referrals = Referral::UserReferrals(auth()->user()->id);
        $user = auth()->user();

        return view("account.referrals")->with(["refferals"=>$referrals,"user"=>$user]);

    }

}
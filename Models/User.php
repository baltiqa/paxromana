<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Commentable;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use App\Http\Controllers\MultisigController;

class User extends Authenticatable implements BannableContract
{
    use Notifiable;
    use SoftDeletes;
    use Commentable;
	use Favoriteability;
    use Bannable;
    use CanFollow,CanBeFollowed;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


	public function getRouteKey()
	{
		return $this->slug;
	}


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'avatar', 'bio', 'withdrawpin',  'unread_messages','pgp-key','2fa',
    ];

    protected $dates = [
        'last_login_at',
    ];



    protected $appends = ['is_banned'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	
	public function comments()
    {
        return $this->hasMany(Comment::class, 'seller_id');
    }

    public function owncomments()
    {
        return $this->hasMany(Comment::class, 'commenter_id');
    }

 public function permiss() {
        return unserialize($this->permissions);
    }


    
    public function getIsBannedAttribute()
    {
        return $this->isBanned();
    }

    public function getIsFollowed($value) {
        return $this->isFollowing($value);
    }

    public function listings() {
      return $this->hasMany('App\Models\Listing');
    }

    public function trashedListings() {
        return $this->hasMany('App\Models\Listing')->withTrashed();
     }
      
    public function countDisputeWin() {
        $countWin = 0;
        foreach($this->disputesSeller as $dispute ) {
            if($dispute->resolved == 1) {
                if($dispute->winner == $this->id) {
                    $countWin = $countWin + 1;
                }
            }
        }
        return $countWin;
    }

    public function isBlocked($user) {
        $checkBlock = UserBlock::where('user_id',$this->id)->where('user_id_blocked',$user->id)->get()->first();

        if($checkBlock == null) {
            return  false;
        }

        return true;
    }

    public function countDisputeLoss() {
        $countLoss = 0;
        foreach($this->disputesSeller as $dispute ) {
            if($dispute->resolved == 1) {
                if($dispute->winner != $this->id) {
                    $countLoss = $countLoss + 1;
                }
            }
        }
        return $countLoss;
    }

    public function countBuyerDisputeWin() {
        $countWin = 0;
        foreach($this->disputesBuyer as $dispute ) {
            if($dispute->resolved == 1) {
                if($dispute->winner == $this->id) {
                    $countWin = $countWin + 1;
                }
            }
        }
        return $countWin;
    }

    public function countBuyerDisputeLoss() {
        $countLoss = 0;
        foreach($this->disputesBuyer as $dispute ) {
            if($dispute->resolved == 1) {
                if($dispute->winner != $this->id) {
                    $countLoss = $countLoss + 1;
                }
            }
        }
        return $countLoss;
    }


    public function disputesBuyer() {
        return $this->hasMany('App\Models\Dispute','buyer_id')->orderBy('created_at','DESC');
    }

    public function disputesSeller() {
        return $this->hasMany('App\Models\Dispute','seller_id')->orderBy('created_at','DESC');
    }

    public function reports() {
        return $this->hasMany('App\Models\ReportedListing','user_id');
    }

    public function ifReportIsDone($user) {
        foreach($user->reports as $report) {
            if($report->reported_user == $this->id && $report->user_id == $user->id) {
                return true;
            }
        }
        return false;
    }

    public function ifReportConversationIsDone($id) {
        foreach($this->reports as $report) {
            if($report->reported_conversation == $id && $report->user_id == $this->id) {
                return true;
            }
        }
        return false;
    }

    public function tickets() {
        return $this->hasMany('App\Models\Tickets','user_id');
    }


    public function getRememberToken()
    {
      return null; // not supported
    }

    public function setAttribute($key, $value)
    {
      $isRememberTokenAttribute = $key == $this->getRememberTokenName();
      if (!$isRememberTokenAttribute)
      {
        parent::setAttribute($key, $value);
      }
    }
    
    public function getAvatarBackground() {
        if(!$this->avatar_background) {
            return '/web/images/nobackground.png';
        }
        return $this->avatar_background;
    }

    public function getAvatar() {
        if(!$this->avatar) {
            return '/web/images/noavatar.png';
        }
        return $this->avatar;
    }


    public function markets() {
        return $this->hasMany('App\Models\UserMarket','user_id');
      }

    public function avg_rating() {
      return number_format($this->comments->avg('rating'), 1) == 0 ? '5.00' : number_format($this->comments->avg('rating'), 2);
    }


    public function count_reviews() {
      return $this->comments()->whereNotNull('rate')->count('rate');
    }
    

    public function normal_orders() {
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    public function getBitcoinTotalSpends() {
        return $this->normal_orders->where('currency','BTC')->sum('price') + $this->normal_orders->where('currency','BTC')->sum('shipping_fee');
    }
    public function getLitecoinTotalSpends() {
        return $this->normal_orders->where('currency','LTC')->sum('price') + $this->normal_orders->where('currency','LTC')->sum('shipping_fee');
    }

    public function getMoneroTotalSpends() {
        return $this->normal_orders->where('currency','XMR')->sum('price') + $this->normal_orders->where('currency','XMR')->sum('shipping_fee');
    }

    public function getBitcoinTotalSales() {
        return $this->orders->where('currency','BTC')->sum('price') + $this->orders->where('currency','BTC')->sum('shipping_fee');
    }
    public function getLitecoinTotalSales() {
        return $this->orders->where('currency','LTC')->sum('price') + $this->orders->where('currency','LTC')->sum('shipping_fee');
    }

    public function getMoneroTotalSales() {
        return $this->orders->where('currency','XMR')->sum('price') + $this->orders->where('currency','XMR')->sum('shipping_fee');
    }

    public function getAvgSpending() {
        return number_format($this->getTotalPriceSpend()/$this->normal_orders->count(), 2);
    }

    public function getAvgASale() {
        return number_format($this->getTotalPriceSales()/$this->orders->count(), 2);
    }

    public function getUnreadMessagesAttribute($value) {
        return max($value, 0);
    }
	
	
    public function orders() {
        return $this->hasMany('App\Models\Order', 'seller_id');
    }

    public function blocks() {
        return $this->hasMany('App\Models\UserBlock', 'user_id');
    }

    public function blockedBy() {
        return $this->hasMany('App\Models\UserBlock', 'user_id_block');
    }

    public function escrowBitcoin(){
        return number_format($this->normal_orders->where('currency','BTC')->whereNotIn('status',['finalized','cancelled'])->sum('price'), 6);
    }

    public function escrowMonero(){
        return number_format($this->normal_orders->where('currency','XMR')->whereNotIn('status',['finalized','cancelled'])->sum('price'), 6);
    }

    public function escrowLitecoin(){
        return number_format($this->normal_orders->where('currency','LTC')->whereNotIn('status',['finalized','cancelled'])->sum('price'), 6);
    }

    public function trusted(){
        if($this->normal_orders->count()>25) {
            return true;
        }
        return false;
    }

    public function marketName($marketTitle) {

        foreach($this->markets as $market) {
            if($marketTitle == $market->market_title) {
                return 'Active';
            } 
        }
        return 'Not active';
    }

    public function getMarketName($marketTitle) {

        foreach($this->markets as $market) {
            if($marketTitle == $market->market_title) {
                return $market;
            } 
        }

    }

    public function xmrBalance($subtract){
        if($subtract > $this->xmr_balance) {
            return false;
        } else {
            $this->xmr_balance = $this->xmr_balance - $subtract;
            $this->save();
            return true;
        }
    }

    public function btcBalance($subtract){
        if($subtract > $this->btc_balance) {
            return false;
        } else {
            $this->btc_balance = $this->btc_balance - $subtract;
            $this->save();
            return true;
        }
    }

    public function ltcBalance($subtract){
        if($subtract > $this->ltc_balance) {
            return false;
        } else {
            $this->ltc_balance = $this->ltc_balance - $subtract;
            $this->save();
            return true;
        }
    }

    public function CheckMultisig($address){

        $multisig = new MultisigController();
        $content = array();
        if($multisig->CheckMultiSigAmount($address)){
            $content = "paid";
        }else{
            $content = "pending";
        }
        return $content;
    }

    public function GetBTCAddress(){
        $addresses = UserAddresses::where("user_id",$this->id)->first();
        return $addresses->btc_address;
    }

    public function GetLTCAddress(){
        $addresses = UserAddresses::where("user_id",$this->id)->first();
        return $addresses->ltc_address;
    }

    public function GetXMRAddress(){
        $addresses = UserAddresses::where("user_id",$this->id)->first();
        return $addresses->xmr_address;
    }


}

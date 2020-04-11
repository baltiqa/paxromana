<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Models\Order;
use App\Models\User;



class Comment extends Model
{
    use SearchableTrait;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


	protected $fillable = [
        'comment',
        'rate',
        'approved',
        'listing_id',
        'commenter_id',
        'seller_id',
    ];

    protected $searchable = [
        'columns' => [
            'comments.comment' => 20,
            'comments.order_id' => 20,
            'users.username' => 10,
        ],
        'joins' => [
            'users' => ['users.id','comments.commenter_id'],
            'users' => ['users.id','comments.seller_id'],
        ],
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

  

    protected $appends = [ 'listing_title','listing_currency','vendor','rating','feedback','created'];

    protected $hidden = [
        'id', 'listing_id','seller_id','commenter_id','order_id','approved','updated_at','created_at','rate','comment',
    ];

	
    public function listing()
    {
        return $this->belongsTo('App\Models\Listing');
    }	
	
    public function commenter()
    {
        return $this->belongsTo('App\Models\User', 'commenter_id');
    }
	
    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function getListingTitleAttribute(){
        $order = Order::find($this->order_id);
        return $order->product_title;
    }

    public function getListingCurrencyAttribute(){
        $order = Order::find($this->order_id);
        return $order->currency;
    }

    public function getVendorAttribute(){
        $user = User::find($this->seller_id);
        return $user->username;
    }
    
    public function getRatingAttribute()
    {
        return (double) $this->rate;
    }

    public function getFeedbackAttribute()
    {
        return $this->comment;
    }

    public function getCreatedAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->timestamp;
    }
}

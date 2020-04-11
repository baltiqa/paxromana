<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculateTimeDiff;
use App\Models\Country;
use App\Models\User;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
use Date;
use Nicolaslopezj\Searchable\SearchableTrait;


class Listing extends Model
{

    use SearchableTrait;
    use Favoriteable;
    use Commentable;
    use SoftDeletes;
    use HashId;
    use CalculateTimeDiff;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    

    protected $canBeRated = true;
    protected $mustBeApproved = false;
    protected $table = 'listings';




    protected $searchable = [
        'columns' => [
            'listings.title' => 20,
            'listings.tags' => 10,
            'listings.description' => 10,
            'users.username' => 30,
        ],
        'joins' => [
            'users' => ['listings.user_id','users.id'],
        ],
    ];

    protected $searchableColumns = ['title', 'tags', 'description'];
    protected $appends = [ 'url','vendor'];

    protected $fillable = [
        'key', 'title', 'price', 'stock', 'unit', 'category_id', 'user_id', 'short_address', 'description', 'spotlight', 'staff_pick', 'is_hidden', 'location', 'lat', 'lng', 'pricing_model_id', 'photos', 'city', 'region',  'country', 'currency', 'is_draft', 'session_duration', 'min_duration', 'max_duration'
    ];
    protected $casts = [
          'photos' => 'array',
          'tags' => 'array',
          'shipping_options' => 'json',
          'variant_options' => 'json',
    ];
    
    protected $spatialFields = [
    ];
    protected $dates = ['expires_at', 'spotlight', 'bold_until', 'priority_until', 'deleted_at', 'ends_at'];


    public function toggleSpotlight()
    {
        $this->spotlight = ($this->spotlight)?null:Carbon::now();
        $this->save();
    }

    public function getIsNewAttribute()
    {
        return !$this->is_published;
    }
	
  
    public function getDaysAgoAttribute($value) {
		return Date::parse($this->created_at)->ago();
	}

    public function getBoldAttribute() {
        if($this->bold_until && $this->bold_until->gt(Carbon::now())) {
            return true;
        }
        return false;
    }

	public function getHashAttribute(): string
    {
        return $this->getRouteKey();
    }

    public function parent() {
        return $this->belongsTo('App\Models\Listing', 'parent_id');
    }

    public function childs(){
        return $this->hasMany('App\Models\Listing','parent_id');
    }
	
	
    public function getEditUrlAttribute() {
        return route('create.edit', [$this]);
    }

    public function getUrlAttribute() {
        return route('auth.listing', [$this->id]);
    }


    public function shipping_options()
    {
        return $this->hasMany('\App\Models\ListingShippingOption');
    }

    public function orders()
    {
        return $this->hasMany('\App\Models\Order');
    }


    public function category()
    {
        return $this->belongsTo('\App\Models\Category');
    }

    public function listing_class()
    {
        return $this->belongsTo('\App\Models\ListingClass');
    }

    public function payment_type()
    {
        return $this->belongsTo('\App\Models\PaymentType');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function getVendorAttribute(): string
    {
        $user = User::find($this->user_id);
        return $user->username;
    }
    

    public function countryNames() {

        $ids = unserialize($this->ships_to);
        $countries = implode (", ", $ids);

        return $countries;
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', 1);
    }

    public function getAdsListing() {
        if($this->priority_until > Carbon::now()) {
            return true;
        }
        return false;
    }

    public function getPhoto() {
        if(!$this->photo) {
            return '/web/images/noimage.png';
        }
        return $this->photo;
    }


}

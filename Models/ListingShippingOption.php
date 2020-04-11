<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HashId;

class ListingShippingOption extends Model
{

    use SoftDeletes;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    protected $fillable = [
        'position', 'name', 'price', 'listing_id'
    ];

    protected $hidden = [
        'id', 'listing_id','created_at','deleted_at','updated_at','position',
    ];
   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingClass extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    protected $table = 'listing_classes';

    
}

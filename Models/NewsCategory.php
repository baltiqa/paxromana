<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

class NewsCategory extends Model
{

  use NestableTrait;

  protected $fillable = [
    'name', 'parent_id'
  ];

    public function news() {
        return $this->hasMany('App\Models\News');
      }


}

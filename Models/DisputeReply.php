<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisputeReply extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    public function dispute(){
        return $this->belongsTo('App\Models\Dispute');
      }
      public function user(){
          return $this->belongsTo('App\Models\User');
      }

      public function moderator()
      {
          return $this->belongsTo('\App\Models\User','adminreply');
      }
}

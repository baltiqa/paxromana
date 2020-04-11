<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
  use \Spiritix\LadaCache\Database\LadaCacheTrait;

  
    public function order(){
        return $this->belongsTo('App\Models\Order');
      }
      public function seller(){
        return $this->belongsTo('App\Models\User','seller_id');
      }
      public function buyer(){
        return $this->belongsTo('App\Models\User','buyer_id');
      }
      public function winnerDispute(){
        return $this->belongsTo('App\Models\User','winner');
      }
      public function replies(){
        return $this->hasMany('App\Models\DisputeReply');
      }

      public function lastMod()
      {
          return $this->belongsTo('\App\Models\User','closed_by');
      }

      public function lastResponse(){
        return $this->replies()->latest()->first();
    }


      public function usernameResponse(){
        $reply = $this->lastResponse();
        if($reply == null) {
            return '';
        }
        if($reply->user_id == 0) {
            return $reply->moderator()->first()->username;
        }

        return $reply->user()->first()->username;

    }

}

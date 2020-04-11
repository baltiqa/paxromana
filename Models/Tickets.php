<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    public function user()
    {
        return $this->belongsTo('\App\Models\User','user_id');
    }
    
    public function replies() {
        return $this->hasMany('App\Models\TicketsReply');
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

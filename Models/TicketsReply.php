<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketsReply extends Model
{
    public function ticket()
    {
        return $this->belongsTo('\App\Models\Tickets','ticket_id');
    }
    
    public function user()
    {
        return $this->belongsTo('\App\Models\User','user_id');
    }

    public function moderator()
    {
        return $this->belongsTo('\App\Models\User','adminreply');
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: faree
 * Date: 1/27/2020
 * Time: 3:28 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserTransactions extends Model
{
  use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $table = 'user_transactions';
    protected $fillable = ['tx_id','user_id','address','currency','amount','type','confirmations','status'];


    public function user() {
        return $this->belongsTo('App\Models\User');
      }
}
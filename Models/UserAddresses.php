<?php
/**
 * Created by PhpStorm.
 * User: faree
 * Date: 1/27/2020
 * Time: 1:47 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class UserAddresses extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = ['user_id','btc_address','ltc_address','xmr_address'];

}
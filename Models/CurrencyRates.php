<?php
/**
 * Created by PhpStorm.
 * User: faree
 * Date: 1/28/2020
 * Time: 3:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CurrencyRates extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    protected $table = 'currency_rates';
    protected $fillable = ['currency_id', 'usd', 'eur', 'gbp', 'cad', 'aud', 'brl', 'dkk', 'nok', 'sek', 'try', 'cny', 'hkd', 'rub', 'inr', 'jpy','usd_atl', 'eur_atl', 'gbp_atl', 'cad_atl', 'aud_atl', 'brl_atl', 'dkk_atl', 'nok_atl', 'sek_atl', 'try_atl', 'cny_atl', 'hkd_atl', 'rub_atl', 'inr_atl', 'jpy_atl'];

}
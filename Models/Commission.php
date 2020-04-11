<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table = "user_commissions";
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    public static function GetCommissions($ref_id,$user_id,$currency){
        $result = Commission::where("referrer_id",$ref_id)->where("user_id",$user_id)->where("currency",$currency)->sum("amount");
        return $result;
    }

}
<?php
namespace App\Models\Filters;
use Illuminate\Database\Eloquent\Builder;
class Escrow implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        switch($value) {
            case 1:
                return $builder->where('payment_type_id', 1)->orWhere('payment_type_id','=', 4);
            break;
            case 2:
                return $builder->where('payment_type_id','=', 2)->orWhere('payment_type_id','=', 3);
            break;
        }

    }
}
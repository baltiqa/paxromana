<?php
namespace App\Models\Filters;
use Illuminate\Database\Eloquent\Builder;
class Order implements Filter
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
                return $builder->orderBy('created_at', 'DESC');
            break;
            case 2:
                return $builder->orderBy('created_at', 'ASC');
            break;
            case 3:
                return $builder->orderBy('price', 'ASC');
            break;
            case 4:
                return $builder->orderBy('price', 'DESC');
            break;
            default:
            return $builder->orderBy('created_at', 'ASC');
             break;
        }
    }
}
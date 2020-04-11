<?php
namespace App\Models\Filters;
use Illuminate\Database\Eloquent\Builder;
class ListingClass implements Filter
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
                return $builder->where('listing_class_id', 1);
            break;
            case 2:
                return $builder->where('listing_class_id', 2);
            break;
            default:
            return $builder->where('listing_class_id', 2);
            break;
        }
    }
}
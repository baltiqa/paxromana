<?php
namespace App\Models\Filters;
use Illuminate\Database\Eloquent\Builder;
class Title implements Filter
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
        return $builder->where('title','LIKE','%'.$value.'%')->orWhere('tags','LIKE','%'.$value.'%');
    }
}
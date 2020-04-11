<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Listing;

class ListingSearch extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    public static function apply(Request $filters)
    {
        $query = static::applyDecoratorsFromRequest($filters, (new Listing)->newQuery());

        return static::getResults($query);
    }
    private static function applyDecoratorsFromRequest(Request $request, Builder $query)
    {
        foreach ($request->all() as $filterName => $value) {
            if($value == null) {
                continue;
            }
            $decorator = static::createFilterDecorator($filterName);
            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }
        }
        return $query->orderBy('priority_until', 'DESC');
    }
    private static function createFilterDecorator($name)
    {
        return __NAMESPACE__ . '\\Filters\\' . studly_case($name);
    }
    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }
    private static function getResults(Builder $query)
    {
        return $query->paginate(10);
    }

}

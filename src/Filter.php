<?php

namespace Studiow\Laravel\Filtering;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Filter\CollectionFilter;
use Studiow\Laravel\Filtering\Filter\EloquentFilter;
use Studiow\Laravel\Filtering\Filter\QueryFilter;

class Filter
{
    public static function make($target)
    {
        if ($target instanceof QueryBuilder) {
            return new QueryFilter($target);
        }

        if ($target instanceof EloquentBuilder) {
            return new EloquentFilter($target);
        }

        if ($target instanceof Model) {
            return new EloquentFilter($target::query());
        }

        return new CollectionFilter(
            new Collection($target)
        );
    }
}

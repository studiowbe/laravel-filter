<?php

namespace Studiow\Laravel\Filtering\Filter;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Exception\UnknownOperatorException;

class QueryFilter implements Filter
{
    private $target;

    public function __construct(Builder $target)
    {
        $this->target = $target;
    }

    public function where(string $key, $operator, $value = null, $boolean = 'and')
    {
        return new self($this->apply($key, $operator, $value, $boolean));
    }

    public function andWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'and');
    }

    public function orWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'or');
    }

    private function apply(string $key, $operator, $value = null, $boolean = 'and')
    {
        if (is_null($value) && ! in_array($operator, ['IS NULL', 'IS NOT NULL'])) {
            return $this->apply($key, '=', $operator, $boolean);
        }

        if (in_array($operator, ['=', '==', '==='])) {
            return $this->target->where($key, '=', $value, $boolean);
        }

        if (in_array($operator, ['!=', '!=='])) {
            return $this->target->where($key, '!=', $value, $boolean);
        }

        if (in_array($operator, ['<>', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE', 'BETWEEN'])) {
            return $this->target->where($key, $operator, $value, $boolean);
        }

        switch ($operator) {
            case 'BETWEEN':
                return $this->target->whereBetween($key, $value, $boolean);
            case 'IN':
                return $this->target->whereIn($key, $value, $boolean);
            case 'NOT IN':
                return $this->target->whereNotIn($key, $value, $boolean);
            case 'IS NULL':
                return $this->target->whereNull($key, $boolean);
            case 'IS NOT NULL':
                return $this->target->whereNotNull($key, $boolean);
            case 'ILIKE':
                $find = '%'.strtolower($value).'%';

                return $this->target->whereRaw("LOWER(`{$key}`) LIKE ?", [$find]);
            case 'NOT ILIKE':
                $find = '%'.strtolower($value).'%';

                return $this->target->whereRaw("LOWER(`{$key}`) NOT LIKE ?", [$find]);
        }

        throw UnknownOperatorException::forOperator($operator);
    }

    public function items(): Collection
    {
        return $this->target->get();
    }

    public function query(): Builder
    {
        return $this->target;
    }
}

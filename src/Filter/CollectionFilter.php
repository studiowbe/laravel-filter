<?php

namespace Studiow\Laravel\Filtering\Filter;

use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Exception\UnknownOperatorException;

class CollectionFilter implements Filter
{
    private $target;

    private $previous;

    public function __construct(Collection $target, ?Collection $previous = null)
    {
        $this->target = $target;
        $this->previous = $previous ?? $target;
    }

    public function where(string $key, $operator, $value = null, $boolean = 'and')
    {
        if ($boolean === 'or') {
            return new self(
                $this->target->merge(
                    $this->apply($this->previous, $key, $operator, $value)
                ), $this->previous
            );
        }

        return new self(
            $this->apply($this->target, $key, $operator, $value), $this->target
        );
    }

    public function andWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'and');
    }

    public function orWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'or');
    }

    private function apply(Collection $target, string $key, $operator, $value = null): Collection
    {
        if (is_null($value) && ! in_array($operator, ['IS NULL', 'IS NOT NULL'])) {
            return $this->apply($target, $key, '=', $operator);
        }

        if (in_array($operator, ['=', '==', '!=', '<>', '<', '>', '<=', '>=', '===', '!=='])) {
            return $target->where($key, $operator, $value);
        }

        switch ($operator) {
            case 'BETWEEN':
                return $target->whereBetween($key, $value);
            case 'IN':
                return $target->whereIn($key, $value);
            case 'NOT IN':
                return $target->whereNotIn($key, $value);
            case 'LIKE':
                return $target->filter($this->operatorForLike($key, $value));
            case 'NOT LIKE':
                return $target->reject($this->operatorForLike($key, $value));
            case 'IS NULL':
                return $target->filter($this->operatorForIsNull($key));
            case 'IS NOT NULL':
                return $target->reject($this->operatorForIsNull($key));
        }

        throw UnknownOperatorException::forOperator($operator);
    }

    private function operatorForLike($key, $value)
    {
        $pattern = str_replace('%', '*', $value);

        return function ($item) use ($key, $pattern) {
            return fnmatch($pattern, data_get($item, $key));
        };
    }

    private function operatorForIsNull($key)
    {
        return function ($item) use ($key) {
            return is_null(data_get($item, $key));
        };
    }

    public function items(): Collection
    {
        return $this->target;
    }
}

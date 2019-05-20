<?php

namespace Studiow\Laravel\Filtering\Filter;

use Illuminate\Support\Collection;

interface Filter
{
    public function where(string $key, $operator, $value = null, $boolean = 'and');

    public function andWhere(string $key, $operator, $value = null);

    public function orWhere(string $key, $operator, $value = null);

    public function items(): Collection;
}

<?php

namespace Studiow\Laravel\Filtering\Test\Filter;

use Illuminate\Database\Eloquent\Builder;
use Orchestra\Testbench\TestCase;
use Studiow\Laravel\Filtering\Filter\EloquentFilter;
use Studiow\Laravel\Filtering\Test\Helper\Car;
use Studiow\Laravel\Filtering\Test\Helper\SupportsOperators;

class EloquentFilterTest extends TestCase
{
    use SupportsOperators;

    protected function getBuilder(): Builder
    {
        return Car::query();
    }

    public function testItSupportsOperators()
    {
        $this->assertOperatorsAreSupported(new EloquentFilter($this->getBuilder()));
    }

    public function testAndWhere()
    {
        $filter = new EloquentFilter($this->getBuilder());

        $filter->where('color', 'red')->andWhere('color', 'blue');

        $this->assertEquals(
            'select * from `cars` where `color` = ? and `color` = ?',
            $filter->query()->toSql()
        );
    }

    public function testOrWhere()
    {
        $filter = new EloquentFilter($this->getBuilder());

        $filter->where('color', 'red')->orWhere('color', 'blue');

        $this->assertEquals(
            'select * from `cars` where `color` = ? or `color` = ?',
            $filter->query()->toSql()
        );

        $this->assertEquals(['red', 'blue'], $filter->query()->getBindings());
    }
}

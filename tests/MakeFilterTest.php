<?php

namespace Studiow\Laravel\Filtering\Test;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Studiow\Laravel\Filtering\Filter;
use Studiow\Laravel\Filtering\Filter\CollectionFilter;
use Studiow\Laravel\Filtering\Filter\EloquentFilter;
use Studiow\Laravel\Filtering\Filter\QueryFilter;
use Studiow\Laravel\Filtering\Test\Helper\Car;

class MakeFilterTest extends FilterTest
{
    public function testMakeFilterFromCollection()
    {
        $this->assertInstanceOf(CollectionFilter::class, Filter::make(new Collection()));
    }

    public function testMakeFilterFromArray()
    {
        $this->assertInstanceOf(CollectionFilter::class, Filter::make([]));
    }

    public function testMakeFilterFromEloquentBuilder()
    {
        $this->assertInstanceOf(QueryFilter::class, Filter::make(DB::table('cars')));
    }

    public function testMakeFilterFromQueryBuilder()
    {
        $this->assertInstanceOf(EloquentFilter::class, Filter::make(Car::query()));
    }

    public function testMakeFilterFromModelBuilder()
    {
        $car = Car::make([]);
        $this->assertInstanceOf(EloquentFilter::class, Filter::make($car));
    }
}

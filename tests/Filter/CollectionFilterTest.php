<?php

namespace Studiow\Laravel\Filtering\Test\Filter;

use Orchestra\Testbench\TestCase;
use Studiow\Laravel\Filtering\Filter\CollectionFilter;
use Studiow\Laravel\Filtering\Test\Helper\SupportsOperators;

class CollectionFilterTest extends TestCase
{
    use SupportsOperators;

    public function testItSupportsOperators()
    {
        $filter = new CollectionFilter(collect([]));
        $this->assertOperatorsAreSupported($filter);
    }

    public function testOrWhere()
    {
        $cars = new CollectionFilter(collect([
            ['color' => 'blue', 'owner' => 'person_1', 'wheels' => 4],
            ['color' => 'purple', 'owner' => 'person_1', 'wheels' => 4],
            ['color' => 'red', 'owner' => 'person_2', 'wheels' => 4],
            ['color' => 'yellow', 'owner' => 'person_3', 'wheels' => 3],
        ]));

        $yellowReds = $cars->where('color', 'red')->orWhere('color', 'yellow');

        $this->assertTrue($yellowReds->items()->contains('color', 'red'));
        $this->assertTrue($yellowReds->items()->contains('color', 'yellow'));
        $this->assertEquals(2, $yellowReds->items()->count());

        $threeWheeled = $yellowReds->andWhere('wheels', 3);

        $this->assertTrue($threeWheeled->items()->contains('color', 'yellow'));
        $this->assertEquals(1, $threeWheeled->items()->count());
    }

    public function testAndWhere()
    {
        $cars = new CollectionFilter(collect([
            ['color' => 'blue', 'owner' => 'person_1', 'plate' => 'ABC-123'],
            ['color' => 'purple', 'owner' => 'person_1', 'plate' => 'DEF-123'],
            ['color' => 'red', 'owner' => 'person_2', 'plate' => 'GHI-123'],
            ['color' => 'yellow', 'owner' => 'person_3', 'plate' => 'JKL-123'],
        ]));

        $found = $cars->where('owner', 'person_1')->andWhere('color', 'blue');
        $this->assertTrue($found->items()->contains('plate', 'ABC-123'));
        $this->assertEquals(1, $found->items()->count());
    }
}

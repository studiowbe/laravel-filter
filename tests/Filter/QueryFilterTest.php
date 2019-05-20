<?php

namespace Studiow\Laravel\Filtering\Test\Filter;

use Illuminate\Database\Query\Builder;
use Studiow\Laravel\Filtering\Filter\QueryFilter;
use Studiow\Laravel\Filtering\Test\FilterTest;
use Studiow\Laravel\Filtering\Test\Helper\SupportsOperators;

class QueryFilterTest extends FilterTest
{
    use SupportsOperators;

    protected function setUp(): void
    {
        parent::setUp();
        include_once __DIR__.'/../database/migrations/create_test_tables.php';
        (new \TestTables())->up();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getBuilder(): Builder
    {
        return app('DB')::table('cars');
    }

    public function testItSupportsOperators()
    {
        $this->assertOperatorsAreSupported(new QueryFilter($this->getBuilder()));
    }

    public function testAndWhere()
    {
        $filter = new QueryFilter($this->getBuilder());

        $filter->where('color', 'red')->andWhere('color', 'blue');

        $this->assertEquals(
            'select * from "cars" where "color" = ? and "color" = ?',
            $filter->query()->toSql()
        );
    }

    public function testOrWhere()
    {
        $filter = new QueryFilter($this->getBuilder());

        $filter->where('color', 'red')->orWhere('color', 'blue');

        $this->assertEquals(
            'select * from "cars" where "color" = ? or "color" = ?',
            $filter->query()->toSql()
        );

        $this->assertEquals(['red', 'blue'], $filter->query()->getBindings());
    }
}

<?php

namespace Studiow\Laravel\Filtering\Test;

use Orchestra\Testbench\TestCase;

abstract class FilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        include_once __DIR__.'/database/migrations/create_test_tables.php';
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
}

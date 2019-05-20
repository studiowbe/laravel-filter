<?php

namespace Studiow\Laravel\Filtering\Test;

use PHPUnit\Framework\TestCase;
use Studiow\Laravel\Filtering\Filter\Filter;

class DocumentationExamplesTest extends TestCase
{
    private function getFilter(): Filter
    {
        $collection = collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        return \Studiow\Laravel\Filtering\Filter::make($collection);
    }

    public function testSimpleExample()
    {
        $filtered = $this->getFilter()->where('price', 100)->items();

        $this->assertEquals([
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Door', 'price' => 100],
        ], array_values($filtered->all()));
    }

    public function testWithOperatorExample()
    {
        $cheap = $this->getFilter()->where('price', '<', 150)->items();

        $this->assertEquals([
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Door', 'price' => 100],
        ], array_values($cheap->all()));
    }

    public function testCombiningAndExample()
    {
        $cheapChairs = $this->getFilter()
            ->where('product', '=', 'Chair')
            ->where('price', '<', 150)
            ->items();

        $this->assertEquals([
            ['product' => 'Chair', 'price' => 100],
        ], array_values($cheapChairs->all()));
    }

    public function testCombiningOrExample()
    {
        $cheapOrBookcase = $this->getFilter()
            ->where('price', '<', 150)
            ->orWhere('product', 'Bookcase')->items();

        $this->assertEquals([
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Door', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
        ], array_values($cheapOrBookcase->all()));
    }
}

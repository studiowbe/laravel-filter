<?php

namespace Studiow\Laravel\Filtering\Test\Helper;

use Studiow\Laravel\Filtering\Filter\Filter;

trait SupportsOperators
{
    public function assertOperatorsAreSupported(Filter $filter)
    {
        $singleValue = ['=', '==', '!=', '<>', '<', '>', '<=', '>=', '===', '!==', 'LIKE', 'NOT LIKE'];
        $multiValue = ['BETWEEN', 'IN', 'NOT IN'];
        $nullValue = ['IS NULL', 'IS NOT NULL'];

        $expectedInstance = get_class($filter);
        foreach ($singleValue as $operator) {
            $this->assertInstanceOf(
                $expectedInstance,
                $filter->where('key', $operator, 'value')
            );
        }

        foreach ($multiValue as $operator) {
            $this->assertInstanceOf(
                $expectedInstance,
                $filter->where('key', $operator, ['value'])
            );
        }

        foreach ($nullValue as $operator) {
            $this->assertInstanceOf(
                $expectedInstance,
                $filter->where('key', $operator)
            );
        }
    }
}

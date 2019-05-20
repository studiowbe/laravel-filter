<?php

namespace Studiow\Laravel\Filtering\Exception;

use InvalidArgumentException;

class UnknownOperatorException extends InvalidArgumentException
{
    public static function forOperator(string $operator)
    {
        return new self(
            sprintf('Unknown operator "%s"', $operator)
        );
    }
}

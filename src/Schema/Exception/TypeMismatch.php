<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Exception;

use function gettype;
use function implode;
use function sprintf;

// Validation for 'type' keyword failed against a given data
class TypeMismatch extends KeywordMismatch
{
    /**
     * @param string[] $expected
     *
     * @return TypeMismatch
     */
    public static function becauseTypeDoesNotMatch(array $expected, mixed $value): self
    {
        $exception          = new self(sprintf("Value expected to be '%s', but '%s' given.", implode(', ', $expected), gettype($value)));
        $exception->data    = $value;
        $exception->keyword = 'type';

        return $exception;
    }
}

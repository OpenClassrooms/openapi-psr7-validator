<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Exception;

use Throwable;

// Indicates that data was not matched against a schema's keyword
class KeywordMismatch extends SchemaMismatch
{
    /** @var string */
    protected $keyword;

    /** @return KeywordMismatch */
    public static function fromKeyword(string $keyword, mixed $data, string|null $message = null, Throwable|null $prev = null): self
    {
        $instance          = new self('Keyword validation failed: ' . $message, 0, $prev);
        $instance->keyword = $keyword;
        $instance->data    = $data;

        return $instance;
    }

    public function keyword(): string
    {
        return $this->keyword;
    }
}

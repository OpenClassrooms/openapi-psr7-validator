<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Exception;

use cebe\openapi\spec\Schema;

class TooManyValidSchemas extends KeywordMismatch
{
    /** @var Schema[] */
    protected $validSchemas = [];

    /**
     * @param Schema[] $validSchemas
     *
     * @return self
     */
    public static function fromKeywordWithValidSchemas(
        string $keyword,
        mixed $data,
        array $validSchemas,
        string|null $message = null,
    ): KeywordMismatch {
        $instance               = new self('Keyword validation failed: ' . $message, 0);
        $instance->keyword      = $keyword;
        $instance->data         = $data;
        $instance->validSchemas = $validSchemas;

        return $instance;
    }

    /** @return Schema[] */
    public function validSchemas(): array
    {
        return $this->validSchemas;
    }
}

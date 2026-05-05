<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Exception;

use Throwable;

class NotEnoughValidSchemas extends KeywordMismatch
{
    /** @var Throwable[] */
    protected $innerExceptions = [];

    /**
     * @param Throwable[] $innerExceptions
     *
     * @return self
     */
    public static function fromKeywordWithInnerExceptions(
        string $keyword,
        mixed $data,
        array $innerExceptions,
        string|null $message = null,
    ): KeywordMismatch {
        $instance                  = new self('Keyword validation failed: ' . $message, 0);
        $instance->keyword         = $keyword;
        $instance->data            = $data;
        $instance->innerExceptions = $innerExceptions;

        return $instance;
    }

    /** @return Throwable[] */
    public function innerExceptions(): array
    {
        return $this->innerExceptions;
    }
}

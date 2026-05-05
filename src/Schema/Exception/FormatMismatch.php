<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Exception;

use function sprintf;

// Indicates that data did not match a given type's "format"
class FormatMismatch extends TypeMismatch
{
    /** @var string */
    protected $format;

    /** @return FormatMismatch */
    public static function fromFormat(string $format, mixed $value, string $type): self
    {
        $i          = new self(sprintf("Value '%s' does not match format %s of type %s", $value, $format, $type));
        $i->format  = $format;
        $i->data    = $value;
        $i->keyword = 'type';

        return $i;
    }

    public function format(): string
    {
        return $this->format;
    }
}

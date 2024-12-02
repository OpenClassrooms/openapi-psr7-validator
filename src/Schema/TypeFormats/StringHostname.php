<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

use function filter_var;

use const FILTER_VALIDATE_DOMAIN;

class StringHostname
{
    public function __invoke(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_DOMAIN) !== false;
    }
}

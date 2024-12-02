<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

use OpenClassrooms\Uri\Exceptions\SyntaxError;
use OpenClassrooms\Uri\UriString;

class StringURI
{
    public function __invoke(string $value): bool
    {
        try {
            // namespace 'OpenClassrooms\Uri' is provided by multiple packages, but PHPStan does not support merging them
            // @phpstan-ignore-next-line
            UriString::parse($value);

            return true;
        } catch (SyntaxError $error) {
            return false;
        }
    }
}

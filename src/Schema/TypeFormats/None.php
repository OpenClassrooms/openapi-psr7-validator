<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

// This format is used for non-meaningful formats like int64,int32
class None
{
    public function __invoke(mixed $value): bool
    {
        return true;
    }
}

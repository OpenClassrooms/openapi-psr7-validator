<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR7;

interface CacheableSchemaFactory extends SchemaFactory
{
    public function getCacheKey(): string;
}

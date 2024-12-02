<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR7;

use cebe\openapi\spec\OpenApi;

interface ReusableSchema
{
    public function getSchema(): OpenApi;
}

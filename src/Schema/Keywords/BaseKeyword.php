<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Keywords;

use cebe\openapi\spec\Schema as CebeSchema;

abstract class BaseKeyword
{
    /** @var CebeSchema */
    protected $parentSchema;

    public function __construct(CebeSchema $parentSchema)
    {
        $this->parentSchema = $parentSchema;
    }
}

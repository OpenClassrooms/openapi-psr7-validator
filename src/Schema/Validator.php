<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema;

use cebe\openapi\spec\Schema;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\SchemaMismatch;

interface Validator
{
    /** @throws SchemaMismatch if data does not match given schema. */
    public function validate(mixed $data, Schema $schema, BreadCrumb|null $breadCrumb = null): void;
}

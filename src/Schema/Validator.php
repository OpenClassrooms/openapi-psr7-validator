<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema;

use cebe\openapi\spec\Schema;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\SchemaMismatch;

interface Validator
{
    /**
     * @param mixed $data
     *
     * @throws SchemaMismatch if data does not match given schema.
     */
    public function validate($data, Schema $schema, ?BreadCrumb $breadCrumb = null): void;
}

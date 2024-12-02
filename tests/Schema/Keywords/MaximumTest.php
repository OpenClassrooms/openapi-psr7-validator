<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\Keywords;

use OpenClassrooms\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\SchemaValidator;
use OpenClassrooms\OpenAPIValidation\Tests\Schema\SchemaValidatorTest;

final class MaximumTest extends SchemaValidatorTest
{
    /**
     * @testWith [100]
     *           [0]
     *           [-1]
     */
    public function testMaximumNonexclusiveKeywordGreen(int $data): void
    {
        $spec = <<<SPEC
schema:
  type: number
  maximum: {$data}
SPEC;

        $schema = $this->loadRawSchema($spec);

        (new SchemaValidator())->validate($data, $schema);
        $this->addToAssertionCount(1);
    }

    public function testMaximumExclusiveKeywordGreen(): void
    {
        $spec = <<<SPEC
schema:
  type: number
  maximum: 100
  exclusiveMaximum: true
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = 100;

        try {
            (new SchemaValidator())->validate($data, $schema);
            $this->fail('Validation did not expected to pass');
        } catch (KeywordMismatch $e) {
            $this->assertEquals('maximum', $e->keyword());
            $this->assertEquals('Keyword validation failed: Value 100 must be less than 100', $e->getMessage());
        }
    }
}

<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\Keywords;

use OpenClassrooms\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\SchemaValidator;
use OpenClassrooms\OpenAPIValidation\Tests\Schema\SchemaValidatorTest;

final class EnumTest extends SchemaValidatorTest
{
    public function testItValidatesEnumGreen(): void
    {
        $spec = <<<SPEC
schema:
  type: string
  enum:
  - a
  - b
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = 'a';

        (new SchemaValidator())->validate($data, $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesEnumRed(): void
    {
        $spec = <<<SPEC
schema:
  type: string
  enum: 
  - a
  - b
SPEC;

        $schema = $this->loadRawSchema($spec);
        $data   = 'c';

        try {
            (new SchemaValidator())->validate($data, $schema);
            $this->fail('Validation did not expected to pass');
        } catch (KeywordMismatch $e) {
            $this->assertEquals('enum', $e->keyword());
        }
    }
}

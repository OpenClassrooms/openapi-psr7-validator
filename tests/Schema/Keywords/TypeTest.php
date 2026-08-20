<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\Keywords;

use OpenClassrooms\OpenAPIValidation\Schema\Exception\TypeMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\SchemaValidator;
use OpenClassrooms\OpenAPIValidation\Tests\Schema\SchemaValidatorTest;
use stdClass;

final class TypeTest extends SchemaValidatorTest
{
    /**
     * @return mixed[][]
     */
    public function validDataProvider(): array
    {
        return [
            ['string', null, 'string value'],
            ['object', null, ['a' => 1]],
            ['object', null, []],
            ['object', null, new stdClass()],
            ['array', null, ['a', 'b']],
            ['array', null, []],
            ['boolean', null, true],
            ['boolean', null, false],
            ['number', null, 12],
            ['number', null, 0.123],
            ['integer', null, 12],
        ];
    }

    /**
     * @param mixed $validValue
     *
     * @dataProvider validDataProvider
     */
    public function testItValidatesTypeGreen(string $type, ?string $format, $validValue): void
    {
        $spec = <<<SPEC
schema:
  type: $type\n
SPEC;
        if ($format) {
            $spec .= <<<SPEC
  format: $format\n
SPEC;
        }

        $schema = $this->loadRawSchema($spec);

        (new SchemaValidator())->validate($validValue, $schema);
        $this->addToAssertionCount(1);
    }

    /**
     * @param mixed $invalidValue
     *
     * @dataProvider invalidDataProvider
     */
    public function testItValidatesTypeRed(string $type, $invalidValue): void
    {
        $spec = <<<SPEC
schema:
  type: $type\n
SPEC;

        $schema = $this->loadRawSchema($spec);

        $this->expectException(TypeMismatch::class);
        (new SchemaValidator())->validate($invalidValue, $schema);
    }

    /**
     * @return mixed[][]
     */
    public function invalidDataProvider(): array
    {
        return [
            ['string', 12],
            ['object', 'not object'],
            ['object', [1]],
            ['array', ['a' => 1, 'b' => 2]], // this is not a plain array (a-la JSON)
            ['array', new stdClass()],
            ['boolean', [1, 2]],
            ['boolean', 'True'],
            ['boolean', ''],
            ['boolean', 0],
            ['number', []],
            ['number', '12'],
            ['number', '0.123'],
            ['number', '-0.123'],
            ['integer', 12.55],
            ['integer', '12'],
            ['integer', '-12'],
            ['integer', new stdClass()],
            ['integer', 1.0],
        ];
    }
}

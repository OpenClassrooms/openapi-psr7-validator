<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\Keywords;

use OpenClassrooms\OpenAPIValidation\Schema\Exception\FormatMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\SchemaValidator;
use OpenClassrooms\OpenAPIValidation\Schema\TypeFormats\FormatsContainer;
use OpenClassrooms\OpenAPIValidation\Tests\Schema\SchemaValidatorTestCase;

final class TypeFormatTest extends SchemaValidatorTestCase
{
    public function testItValidatesTypeFormatGreen(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: email
SPEC;

        $schema = $this->loadRawSchema($spec);
        (new SchemaValidator())->validate('valid@email.org', $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesTypeInvalidFormatRed(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: email
SPEC;

        $schema = $this->loadRawSchema($spec);

        try {
            (new SchemaValidator())->validate('invalid email', $schema);
            $this->fail('Validation did not expected to pass');
        } catch (FormatMismatch $e) {
            $this->assertEquals('email', $e->format());
        }
    }

    public function testItUnexpectedFormatIgnoredGreen(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: unexpected
SPEC;

        $schema = $this->loadRawSchema($spec);
        (new SchemaValidator())->validate('valid@email.org', $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesSafeUriGreen(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: safe-uri
SPEC;

        $schema = $this->loadRawSchema($spec);
        (new SchemaValidator())->validate('https://example.org/path', $schema);
        $this->addToAssertionCount(1);
    }

    public function testItValidatesSafeUriRed(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: safe-uri
SPEC;

        $schema = $this->loadRawSchema($spec);

        try {
            (new SchemaValidator())->validate('https://localhost/path', $schema);
            $this->fail('Validation did not expected to pass');
        } catch (FormatMismatch $e) {
            $this->assertEquals('safe-uri', $e->format());
        }
    }

    public function testItValidatesSafeUriWithUserInfoRed(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: safe-uri
SPEC;

        $schema = $this->loadRawSchema($spec);

        try {
            (new SchemaValidator())->validate('https://user@example.org/path', $schema);
            $this->fail('Validation did not expected to pass');
        } catch (FormatMismatch $e) {
            $this->assertEquals('safe-uri', $e->format());
        }
    }

    public function testItAllowsCustomFormatGreen(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: unexpected
SPEC;

        $unexpectedFormat = new class ()
        {
            public function __invoke(mixed $value): bool
            {
                return $value === 'good value';
            }
        };
        FormatsContainer::registerFormat('string', 'unexpected', $unexpectedFormat);

        $schema = $this->loadRawSchema($spec);
        (new SchemaValidator())->validate('good value', $schema);
        $this->addToAssertionCount(1);
    }

    public function testItAllowsCustomFormatRed(): void
    {
        $spec = <<<'SPEC'
schema:
  type: string
  format: unexpected
SPEC;

        $customFormat = static function ($value): bool {
            return $value === 'good value';
        };
        FormatsContainer::registerFormat('string', 'unexpected', $customFormat);

        try {
            $schema = $this->loadRawSchema($spec);
            (new SchemaValidator())->validate('bad value', $schema);
            $this->fail('Validation did not expected to pass');
        } catch (FormatMismatch $e) {
            $this->assertEquals('unexpected', $e->format());
        }
    }
}

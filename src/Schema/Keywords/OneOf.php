<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\Keywords;

use cebe\openapi\spec\Schema as CebeSchema;
use OpenClassrooms\OpenAPIValidation\Schema\BreadCrumb;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\InvalidSchema;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\NotEnoughValidSchemas;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use OpenClassrooms\OpenAPIValidation\Schema\Exception\TooManyValidSchemas;
use OpenClassrooms\OpenAPIValidation\Schema\SchemaValidator;
use Respect\Validation\Validator;
use Throwable;

use function count;
use function sprintf;

class OneOf extends BaseKeyword
{
    /** @var int this can be Validator::VALIDATE_AS_REQUEST or Validator::VALIDATE_AS_RESPONSE */
    protected $validationDataType;
    /** @var BreadCrumb */
    protected $dataBreadCrumb;

    public function __construct(CebeSchema $parentSchema, int $type, BreadCrumb $breadCrumb)
    {
        parent::__construct($parentSchema);
        $this->validationDataType = $type;
        $this->dataBreadCrumb     = $breadCrumb;
    }

    /**
     * This keyword's value MUST be an array.  This array MUST have at least
     * one element.
     *
     * Inline or referenced schema MUST be of a Schema Object and not a standard JSON Schema.
     *
     * An instance validates successfully against this keyword if it
     * validates successfully against exactly one schema defined by this
     * keyword's value.
     *
     * @param mixed        $data
     * @param CebeSchema[] $oneOf
     *
     * @throws KeywordMismatch
     */
    public function validate($data, array $oneOf): void
    {
        try {
            Validator::arrayVal()->assert($oneOf);
            Validator::each(Validator::instance(CebeSchema::class))->assert($oneOf);
        } catch (Throwable $e) {
            throw InvalidSchema::becauseDefensiveSchemaValidationFailed($e);
        }

        // Validate against all schemas
        $schemaValidator = new SchemaValidator($this->validationDataType);
        $innerExceptions = [];
        $validSchemas    = [];

        foreach ($oneOf as $schema) {
            try {
                $schemaValidator->validate($data, $schema, $this->dataBreadCrumb);
                $validSchemas[] = $schema;
            } catch (SchemaMismatch $e) {
                $innerExceptions[] = $e;
            }
        }

        if (count($validSchemas) === 1) {
            return;
        }

        if (count($validSchemas) < 1) {
            throw NotEnoughValidSchemas::fromKeywordWithInnerExceptions(
                'oneOf',
                $data,
                $innerExceptions,
                'Data must match exactly one schema, but matched none'
            );
        }

        throw TooManyValidSchemas::fromKeywordWithValidSchemas(
            'oneOf',
            $data,
            $validSchemas,
            sprintf('Data must match exactly one schema, but matched %d', count($validSchemas))
        );
    }
}

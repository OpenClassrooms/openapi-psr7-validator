<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR7;

use cebe\openapi\spec\OpenApi;
use OpenClassrooms\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use OpenClassrooms\OpenAPIValidation\PSR7\Validators\BodyValidator\BodyValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\Validators\CookiesValidator\CookiesValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\Validators\HeadersValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\Validators\QueryArgumentsValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\Validators\ValidatorChain;
use Psr\Http\Message\RequestInterface;

final class CallbackRequestValidator implements ReusableSchema
{
    /** @var OpenApi */
    protected $openApi;
    /** @var MessageValidator */
    protected $validator;

    public function __construct(OpenApi $schema)
    {
        $this->openApi   = $schema;
        $finder          = new SpecFinder($this->openApi);
        $this->validator = new ValidatorChain(
            new HeadersValidator($finder),
            new CookiesValidator($finder),
            new BodyValidator($finder),
            new QueryArgumentsValidator($finder)
        );
    }

    public function getSchema(): OpenApi
    {
        return $this->openApi;
    }

    /**
     * @throws ValidationFailed
     */
    public function validate(CallbackAddress $opAddr, RequestInterface $serverRequest): void
    {
        $this->validator->validate($opAddr, $serverRequest);
    }
}

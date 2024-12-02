<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR7\Validators;

use OpenClassrooms\OpenAPIValidation\PSR7\MessageValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\OperationAddress;
use Psr\Http\Message\MessageInterface;

final class ValidatorChain implements MessageValidator
{
    /** @var MessageValidator[] */
    private $validators;

    public function __construct(MessageValidator ...$messageValidators)
    {
        $this->validators = $messageValidators;
    }

    /** {@inheritdoc} */
    public function validate(OperationAddress $addr, MessageInterface $message): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($addr, $message);
        }
    }
}

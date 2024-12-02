<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR7;

use OpenClassrooms\OpenAPIValidation\PSR7\Exception\NoPath;
use OpenClassrooms\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Psr\Http\Message\MessageInterface;

interface MessageValidator
{
    /**
     * @throws NoPath
     * @throws ValidationFailed
     */
    public function validate(OperationAddress $addr, MessageInterface $message): void;
}

<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\PSR15\Exception;

use OpenClassrooms\OpenAPIValidation\PSR7\Exception\ValidationFailed;

class InvalidServerRequestMessage extends ValidationFailed
{
    public static function because(ValidationFailed $e): self
    {
        return new self('Server Request message failed validation', 0, $e);
    }
}

<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class StringPhoneNumber
{
    public function __invoke(string $value): bool
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            return $phoneUtil->isValidNumber($phoneUtil->parse($value));
        } catch (NumberParseException $e) {
            return false;
        }
    }
}

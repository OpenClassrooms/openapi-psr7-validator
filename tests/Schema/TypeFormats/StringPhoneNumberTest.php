<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\TypeFormats;

use OpenClassrooms\OpenAPIValidation\Schema\TypeFormats\StringPhoneNumber;
use PHPUnit\Framework\TestCase;

final class StringPhoneNumberTest extends TestCase
{
    /**
     * @dataProvider dateTimeGreenDataProvider
     */
    public function testGreenPhoneNumberTypeFormat(string $phoneNumber): void
    {
        $this->assertTrue((new StringPhoneNumber())($phoneNumber));
    }

    /**
     * @return string[][]
     */
    public function dateTimeGreenDataProvider(): array
    {
        return [
            ['+441234567890'],
            ['+33600000000'],
            ['+15035656566'],
        ];
    }

    /**
     * @dataProvider dateTimeRedDataProvider
     */
    public function testRedPhoneNumberFormat(string $phoneNumber): void
    {
        $this->assertFalse((new StringPhoneNumber())($phoneNumber));
    }

    /**
     * @return string[][]
     */
    public function dateTimeRedDataProvider(): array
    {
        return [
            ['1111111111111111'],
            ['5038875656'],
            ['0656'],
            ['0656565656'],
            ['1985-04-12T23:20:50.52'],
            [''],
            ['somestring'],
        ];
    }
}

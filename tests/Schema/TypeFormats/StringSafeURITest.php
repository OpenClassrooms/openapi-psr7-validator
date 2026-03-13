<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\Schema\TypeFormats;

use OpenClassrooms\OpenAPIValidation\Schema\TypeFormats\StringSafeURI;
use PHPUnit\Framework\TestCase;

class StringSafeURITest extends TestCase
{
    /**
     * @return array<array<string>>
     */
    public function greenSafeURIDataProvider(): array
    {
        return [
            'https public host' => ['https://example.org/path?query=value'],
            'uppercase scheme and host' => ['HTTPS://EXAMPLE.ORG/path'],
            'explicit https default port' => ['https://example.org:443/path'],
            'encoded hash in query is allowed' => ['https://example.org/path?redirect=%23intro'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function redSafeURIDataProvider(): array
    {
        return [
            'relative URI' => ['/path/only'],
            'http public host' => ['http://example.org'],
            'missing host' => ['https:///path'],
            'forbidden scheme javascript' => ['javascript:alert(1)'],
            'forbidden scheme file' => ['file:///etc/passwd'],
            'userinfo not allowed' => ['https://user:pass@example.org/path'],
            'userinfo only user not allowed' => ['https://user@example.org/path'],
            'userinfo empty user not allowed' => ['https://@example.org/path'],
            'fragment not allowed' => ['https://example.org/path#fragment'],
            'empty fragment not allowed' => ['https://example.org/path#'],
            'localhost forbidden' => ['https://localhost/path'],
            'localhost uppercase forbidden' => ['https://LOCALHOST/path'],
            'localhost suffix forbidden' => ['https://service.localhost/path'],
            'local suffix forbidden' => ['https://service.local/path'],
            'internal suffix forbidden' => ['https://service.internal/path'],
            'public ipv4 forbidden for standard url policy' => ['https://1.1.1.1/dns-query'],
            'public ipv6 forbidden for standard url policy' => ['https://[2606:4700:4700::1111]/dns-query'],
            'private ipv4 forbidden' => ['https://10.0.0.1/path'],
            'private ipv6 forbidden' => ['https://[fd00::1]/path'],
            'loopback ipv4 forbidden' => ['https://127.0.0.1/path'],
            'loopback ipv6 forbidden' => ['https://[::1]/path'],
            'link local ipv4 forbidden' => ['https://169.254.1.2/path'],
            'metadata ipv4 forbidden' => ['https://169.254.169.254/latest/meta-data'],
            'single label hostname forbidden' => ['https://example/path'],
            'hostname with trailing dot forbidden' => ['https://example.org./path'],
            'port other than 443 forbidden' => ['https://example.org:444/path'],
            'encoded control chars in host' => ['https://exa%0ample.org/path'],
            'encoded del in host' => ['https://exa%7fample.org/path'],
            'double dots in host forbidden' => ['https://example..org/path'],
            'leading hyphen in host label forbidden' => ['https://-bad.example.org/path'],
            'trailing hyphen in host label forbidden' => ['https://bad-.example.org/path'],
        ];
    }

    /**
     * @dataProvider greenSafeURIDataProvider
     */
    public function testGreenSafeURIFormat(string $uri): void
    {
        $this->assertTrue((new StringSafeURI())($uri));
    }

    /**
     * @dataProvider redSafeURIDataProvider
     */
    public function testRedSafeURIFormat(string $uri): void
    {
        $this->assertFalse((new StringSafeURI())($uri));
    }
}

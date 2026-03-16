<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

use League\Uri\Exceptions\SyntaxError;
use League\Uri\UriString;

class StringSafeURI
{
    private const ALLOWED_SCHEMES = ['https'];

    public function __invoke(string $value): bool
    {
        try {
            // @phpstan-ignore-next-line
            $parts = UriString::parse($value);
        } catch (SyntaxError $error) {
            return false;
        }

        if (!isset($parts['scheme'], $parts['host'])) {
            return false;
        }

        $scheme = strtolower((string)$parts['scheme']);
        if (!in_array($scheme, self::ALLOWED_SCHEMES, true)) {
            return false;
        }

        if (isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        if (isset($parts['fragment'])) {
            return false;
        }

        if (isset($parts['port']) && !$this->isAllowedPort($parts['port'])) {
            return false;
        }

        $host = strtolower((string)$parts['host']);
        if ($host === '' || $this->isLocalHost($host) || $this->containsEncodedControlChars($host)) {
            return false;
        }

        if ($this->isIpLiteral($host) || $this->hasTrailingDot($host)) {
            return false;
        }

        return $this->isStandardPublicDnsHost($host);
    }

    /**
     * @param int|string $port
     */
    private function isAllowedPort($port): bool
    {
        if (is_int($port)) {
            return $port === 443;
        }

        return ctype_digit($port) && (int)$port === 443;
    }

    private function isLocalHost(string $host): bool
    {
        if ($host === 'localhost' || str_ends_with($host, '.localhost')) {
            return true;
        }

        return str_ends_with($host, '.local') || str_ends_with($host, '.internal');
    }

    private function containsEncodedControlChars(string $host): bool
    {
        return preg_match('/%(0[0-9a-f]|1[0-9a-f]|7f)/i', $host) === 1;
    }

    private function isIpLiteral(string $host): bool
    {
        return filter_var($host, FILTER_VALIDATE_IP) !== false || str_contains($host, '%');
    }

    private function hasTrailingDot(string $host): bool
    {
        return str_ends_with($host, '.');
    }

    private function isStandardPublicDnsHost(string $host): bool
    {
        if (strlen($host) > 253 || str_contains($host, '..')) {
            return false;
        }

        $labels = explode('.', $host);
        if (count($labels) < 2) {
            return false;
        }

        foreach ($labels as $label) {
            if ($label === '' || strlen($label) > 63) {
                return false;
            }

            if (preg_match('/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/', $label) !== 1) {
                return false;
            }
        }

        return true;
    }
}

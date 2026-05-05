<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema\TypeFormats;

// Purpose of this class is to allow customizable/extendable list of formats
class FormatsContainer
{
    /** @var mixed[] - array of types->formats->callables */
    private static $list = [
        'string' => [
            'byte' => StringByte::class,
            'date' => StringDate::class,
            'date-time' => StringDateTime::class,
            'email' => StringEmail::class,
            'hostname' => StringHostname::class,
            'phone-number' => StringPhoneNumber::class,
            'uri' => StringURI::class,
            'safe-uri' => StringSafeURI::class,
            'uuid' => StringUUID::class,
            'ipv4' => StringIP4::class,
            'ipv6' => StringIP6::class,
        ],
        'number' => [
            'float' => NumberFloat::class,
            'double' => NumberDouble::class,
        ],
    ];

    /**
     * Empty the list
     */
    public static function flush(): void
    {
        self::$list = [];
    }

    /**
     * Put default formats (shipped with the package)
     */
    public static function addDefaults(): void
    {
        // string
        self::registerFormat('string', 'byte', StringByte::class);
        self::registerFormat('string', 'date', StringDate::class);
        self::registerFormat('string', 'date-time', StringDateTime::class);
        self::registerFormat('string', 'email', StringEmail::class);
        self::registerFormat('string', 'hostname', StringHostname::class);
        self::registerFormat('string', 'phone-number', StringPhoneNumber::class);
        self::registerFormat('string', 'uri', StringURI::class);
        self::registerFormat('string', 'safe-uri', StringSafeURI::class);
        self::registerFormat('string', 'uuid', StringUUID::class);
        self::registerFormat('string', 'ipv4', StringIP4::class);
        self::registerFormat('string', 'ipv6', StringIP6::class);

        // number
        self::registerFormat('number', 'float', NumberFloat::class);
        self::registerFormat('number', 'double', NumberDouble::class);
    }

    /**
     * Add new format to the list
     */
    public static function registerFormat(string $type, string $format, string|callable $fqcn): void
    {
        self::$list[$type][$format] = $fqcn;
    }

    /**
     * Return FQCN for the format validation class
     */
    public static function getFormat(string $type, string $format): string|callable|null
    {
        return self::$list[$type][$format] ?? null;
    }
}

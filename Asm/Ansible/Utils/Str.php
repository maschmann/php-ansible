<?php

declare(strict_types=1);

namespace Asm\Ansible\Utils;

use JsonException;

class Str
{
    /**
     * Validate the provided string is JSON formatted.
     *
     * Not JSON if result is not an object (stdClass).
     * Silent return false on exceptions e.g. Invalid/Incorrect encoding, Array depth more than 512 etc.
     *
     * @param string $value
     * @return bool
     */
    public static function isJsonFormatted(string $value): bool
    {
        try {
            return is_object(json_decode($value, false, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException) {
            return false;
        }
    }
}

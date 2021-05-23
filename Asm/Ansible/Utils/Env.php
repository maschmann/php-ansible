<?php

declare(strict_types=1);

namespace Asm\Ansible\Utils;

class Env
{
    /**
     * @return bool True if the host machine is running Windows
     */
    public static function isWindows(): bool
    {
        return defined('PHP_WINDOWS_VERSION_BUILD');
    }
}

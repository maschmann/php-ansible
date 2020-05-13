<?php
/**
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2016 Metagûsto <info@metagusto.com>
 */

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
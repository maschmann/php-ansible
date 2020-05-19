<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Ansible\Command;

use Psr\Log\LoggerAwareInterface;

/**
 * Interface AnsibleCommandInterface
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface AnsibleCommandInterface extends LoggerAwareInterface
{
    /**
     * Executes a command process.
     * Returns either exitcode or string output if no callback is given.
     *
     * @param callable|null $callback
     * @return integer|string
     */
    public function execute($callback = null);

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    public function getCommandlineArguments(bool $asArray = true);
}

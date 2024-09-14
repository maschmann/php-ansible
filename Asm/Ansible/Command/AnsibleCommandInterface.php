<?php

declare(strict_types=1);

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
     * Returns either exit code or string output if no callback is given.
     *
     * @param callable|null $callback
     * @param array $env
     * @return integer|string
     */
    public function execute(?callable $callback = null, array $env = []): int|string;

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    public function getCommandlineArguments(bool $asArray = true): string|array;
}

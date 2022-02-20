<?php

declare(strict_types=1);

namespace Asm\Ansible\Process;

use Symfony\Component\Process\Process;

/**
 * @package Asm\Ansible\Process
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface ProcessBuilderInterface
{
    /**
     * @param array $arguments arguments for process generation
     * @return ProcessBuilderInterface
     */
    public function setArguments(array $arguments): ProcessBuilderInterface;

    /**
     * @param int $timeout
     * @return ProcessBuilderInterface
     */
    public function setTimeout(int $timeout): ProcessBuilderInterface;

    /**
     * @param string $name name of ENV VAR
     * @param string|int $value
     * @return ProcessBuilderInterface
     */
    public function setEnv(string $name, string|int $value): ProcessBuilderInterface;

    /**
     * @return Process
     */
    public function getProcess(): Process;
}

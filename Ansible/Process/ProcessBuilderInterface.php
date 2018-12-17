<?php
declare(strict_types=1);
/*
 * This file is part of the asm\php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param string $value
     * @return ProcessBuilderInterface
     */
    public function setEnv(string $name, string $value): ProcessBuilderInterface;

    /**
     * @return Process
     */
    public function getProcess(): Process;
}

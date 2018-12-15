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
 * Wrapper for symfony process component to allow for command option/argument collection before execute
 *
 * @package Asm\Ansible\Process
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class ProcessBuilder implements ProcessBuilderInterface
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var string
     */
    private $path;

    /**
     * ProcessBuilder constructor.
     *
     * @param string $prefix
     * @param string $path
     */
    public function __construct(string $prefix, string $path)
    {
        $this->arguments = [$prefix];
        $this->path = $path;
        $this->timeout = 900;
    }

    /**
     * @param array $arguments arguments for process generation
     * @return ProcessBuilderInterface
     */
    public function setArguments(array $arguments): ProcessBuilderInterface
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param int $timeout
     * @return ProcessBuilderInterface
     */
    public function setTimeout(int $timeout): ProcessBuilderInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return Process
     */
    public function getProcess(): Process
    {
        return (
            new Process(
                $this->arguments,
                $this->path
            )
        )->setTimeout($this->timeout);
    }
}

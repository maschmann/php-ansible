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

     * @var array
     */
    private $envVars;

    /**
     * ProcessBuilder constructor.
     *
     * @param string $command The command to run.
     * @param string $path The working directory.
     */
    public function __construct(string $command, string $path)
    {
        $this->arguments = [$command];
        $this->path = $path;
        $this->timeout = 900;
        $this->envVars = [];
    }

    /**
     * @param array $arguments arguments for process generation
     * @return ProcessBuilderInterface
     */
    public function setArguments(array $arguments): ProcessBuilderInterface
    {
        if (!empty($this->arguments)) {
            $this->arguments = array_merge($this->arguments, $arguments);
        } else {
            $this->arguments = $arguments;
        }

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
     * @param string $name name of ENV VAR
     * @param string $value
     * @return ProcessBuilderInterface
     */
    public function setEnv(string $name, string $value): ProcessBuilderInterface
    {
        $this->envVars[$name] = $value;

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
        )
        ->setTimeout($this->timeout)
        ->setEnv($this->envVars);
    }
}

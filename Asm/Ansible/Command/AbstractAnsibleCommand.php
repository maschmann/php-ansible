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

use Asm\Ansible\Process\ProcessBuilderInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Process\Process;

/**
 * Class AbstractAnsibleCommand
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
abstract class AbstractAnsibleCommand
{
    /**
     * Adds a local $logger instance and the setter.
     */
    use LoggerAwareTrait;
    /**
     * @var ProcessBuilderInterface
     */
    protected $processBuilder;

    /**
     * @var Option[]
     */
    private $options;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $baseOptions;

    /**
     * @param ProcessBuilderInterface $processBuilder
     * @param LoggerInterface|null         $logger
     */
    public function __construct(ProcessBuilderInterface $processBuilder, LoggerInterface $logger = null)
    {
        $this->processBuilder = $processBuilder;
        $this->options = [];
        $this->parameters = [];
        $this->baseOptions = [];
        $this->setLogger($logger ?? new NullLogger());
    }

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    protected function prepareArguments(bool $asArray = true)
    {
        $arguments = array_merge(
            [$this->getBaseOptions()],
            $this->getOptions(),
            $this->getParameters()
        );

        if (false === $asArray) {
            $arguments = implode(' ', $arguments);
        }

        return $arguments;
    }

    /**
     * Add an Option.
     *
     * @param string $name
     * @param string $value
     */
    protected function addOption(string $name, string $value): void
    {
        $this->options[] = new Option($name, $value);
    }

    /**
     * Add a parameter.
     *
     * @param string $name
     */
    protected function addParameter(string $name): void
    {
        $this->parameters[] = $name;
    }

    /**
     * Get all options as array.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $options = [];

        foreach ($this->options as $option) {
            $options[] = $option->toString();
        }

        return $options;
    }

    /**
     * Get all parameters as array.
     *
     * @return array
     */
    protected function getParameters(): array
    {
        return $this->parameters;
    }


    /**
     * Add base options to internal storage.
     *
     * @param string $baseOption
     * @return $this
     */
    protected function addBaseOption(string $baseOption)
    {
        $this->baseOptions[] = $baseOption;

        return $this;
    }

    /**
     * Generate base options string.
     *
     * @return string
     */
    protected function getBaseOptions(): string
    {
        return implode(' ', $this->baseOptions);
    }

    /**
     * Check if param is array or string and implode with glue if necessary.
     *
     * @param string|array $param
     * @param string $glue
     * @return string
     */
    protected function checkParam($param, string $glue = ' '): string
    {
        if (is_array($param)) {
            $param = implode($glue, $param);
        }

        return $param;
    }

    /**
     * Creates process with processBuilder builder and executes it.
     *
     * @param callable|null $callback
     * @return int|string
     */
    protected function runProcess($callback = null, array $env = [])
    {
        $process = $this->processBuilder
            ->setArguments(
                $this->prepareArguments()
            )
            ->getProcess();

        // Logging the command
        $this->logger->debug('Executing: ' . $this->getProcessCommandline($process));

        // exit code
        $result = $process->run($callback, $env);

        // text-mode
        if (null === $callback) {
            $result = $process->getOutput();

            if (false === $process->isSuccessful()) {
                $process->getErrorOutput();
            }
        }

        return $result;
    }

    /**
     * Builds the complete commandline inclusive of the environment variables.
     * @param Process $process The process instance.
     * @return string
     */
    protected function getProcessCommandline(Process $process): string
    {
        $commandline = $process->getCommandLine();
        if (count($process->getEnv()) === 0)
            return $commandline;

        // Here: we also need to dump the environment variables
        $vars = [];
        foreach ($process->getEnv() as $var => $value) {
            $vars[] = sprintf('%s=\'%s\'', $var, $value);
        }

        return sprintf('%s %s', implode(' ', $vars), $commandline);
    }
}

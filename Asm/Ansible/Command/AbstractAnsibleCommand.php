<?php

declare(strict_types=1);

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

    protected ProcessBuilderInterface $processBuilder;

    /**
     * @var Option[]
     */
    private array $options;

    private array $parameters;

    private array $baseOptions;

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
    protected function prepareArguments(bool $asArray = true): string|array
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
     * @param int|string $value
     */
    protected function addOption(string $name, int|string $value): void
    {
        $this->options[] = new Option($name, (string)$value);
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
    protected function addBaseOption(string $baseOption): self
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
    protected function checkParam(string|array $param, string $glue = ' '): string
    {
        if (is_array($param)) {
            $param = implode($glue, $param);
        }

        return $param;
    }

    /**
     * Creates process with processBuilder builder and executes it.
     * Has to return the process exit code in case of error
     *
     * @param callable|null $callback
     * @param array $callback
     * @return int|string
     */
    protected function runProcess(?callable $callback, array $env = []): int|string
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

        // if a callback is set, we return the exit code
        if (null !== $callback) {
            return $result;
        }

        // if no callback is set, and the process is not successful, we return the output
        if (false === $process->isSuccessful()) {
            return $process->getErrorOutput();
        }

        // if no callback is set, and the process is successful, we return the output
        return $process->getOutput();
    }

    /**
     * Builds the complete commandline inclusive of the environment variables.
     * @param Process $process The process instance.
     * @return string
     */
    protected function getProcessCommandline(Process $process): string
    {
        $commandline = $process->getCommandLine();
        if (count($process->getEnv()) === 0) {
            return $commandline;
        }

        // Here: we also need to dump the environment variables
        $vars = [];
        foreach ($process->getEnv() as $var => $value) {
            $vars[] = sprintf('%s=\'%s\'', $var, $value);
        }

        return sprintf('%s %s', implode(' ', $vars), $commandline);
    }
}

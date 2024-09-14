<?php

declare(strict_types=1);

namespace Asm\Ansible;

use Asm\Ansible\Command\AnsibleGalaxy;
use Asm\Ansible\Command\AnsibleGalaxyInterface;
use Asm\Ansible\Command\AnsiblePlaybook;
use Asm\Ansible\Command\AnsiblePlaybookInterface;
use Asm\Ansible\Exception\CommandException;
use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Process\ProcessBuilderInterface;
use Asm\Ansible\Utils\Env;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Ansible command factory
 *
 * @package Asm\Ansible
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class Ansible implements LoggerAwareInterface
{
    /**
     * Adds a local $logger instance and the setter.
     */
    use LoggerAwareTrait;

    private const DEFAULT_TIMEOUT = 300;

    private string $playbookCommand;

    private string $galaxyCommand;

    private string $ansibleBaseDir;

    private int $timeout;

    /**
     * @param string $ansibleBaseDir base directory of ansible project structure
     * @param string $playbookCommand path to playbook executable, default ansible-playbook
     * @param string $galaxyCommand path to galaxy executable, default ansible-galaxy
     */
    public function __construct(string $ansibleBaseDir, string $playbookCommand = '', string $galaxyCommand = '')
    {
        $this->ansibleBaseDir = $this->checkDir($ansibleBaseDir);
        $this->playbookCommand = $this->checkCommand($playbookCommand, 'ansible-playbook');
        $this->galaxyCommand = $this->checkCommand($galaxyCommand, 'ansible-galaxy');

        $this->timeout = Ansible::DEFAULT_TIMEOUT;
        $this->logger = new NullLogger();
    }

    /**
     * AnsiblePlaybook instance creator
     *
     * @return AnsiblePlaybookInterface
     */
    public function playbook(): AnsiblePlaybookInterface
    {
        return new AnsiblePlaybook(
            $this->createProcess($this->playbookCommand),
            $this->logger
        );
    }

    /**
     * AnsibleGalaxy instance creator
     *
     * @return AnsibleGalaxyInterface
     */
    public function galaxy(): AnsibleGalaxyInterface
    {
        return new AnsibleGalaxy(
            $this->createProcess($this->galaxyCommand),
            $this->logger
        );
    }

    /**
     * Set process timeout in seconds.
     *
     * @param int $timeout
     * @return Ansible
     */
    public function setTimeout(int $timeout): Ansible
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $prefix base command
     * @return ProcessBuilderInterface
     */
    private function createProcess(string $prefix): ProcessBuilderInterface
    {
        $process = new ProcessBuilder($prefix, $this->ansibleBaseDir);

        return $process->setTimeout($this->timeout);
    }

    /**
     * @param string $command
     * @param string $default
     * @return string
     * @throws CommandException
     */
    private function checkCommand(string $command, string $default): string
    {
        // normally ansible is in /usr/local/bin/*
        if (empty($command)) {
            if (Env::isWindows()) {
                return $default;
            }

            // not testable without ansible installation
            if (null === shell_exec('which ' . $default)) {
                throw new CommandException(sprintf('No "%s" executable present in PATH!', $default));
            }

            return $default;
        }

        // Here: we have a given command, just need to check it exists and it's executable
        if (!is_file($command)) {
            throw new CommandException(sprintf('Command "%s" does not exist!', $command));
        }

        if (!$this->isExecutable($command)) {
            throw new CommandException(sprintf('Command "%s" is not executable!', $command));
        }

        return $command;
    }

    /**
     * @param string $dir directory to check
     * @return string
     * @throws CommandException
     */
    private function checkDir(string $dir): string
    {
        if (!is_dir($dir)) {
            throw new CommandException('Ansible project root ' . $dir . ' not found!');
        }

        return $dir;
    }

    /**
     * @param string $command
     * @return bool
     */
    private function isExecutable(string $command): bool
    {
        if (empty($command)) {
            return false;
        }

        if (!Env::isWindows()) {
            return is_executable($command);
        }

        foreach (['exe', 'com', 'bat', 'cmd', 'ps1'] as $ext) {
            if (strtolower(substr($command, -3, 3)) === $ext) {
                return true;
            }
        }

        return false;
    }
}

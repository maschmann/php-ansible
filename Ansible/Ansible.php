<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Ansible;

use Asm\Ansible\Command\AnsibleGalaxy;
use Asm\Ansible\Command\AnsibleGalaxyInterface;
use Asm\Ansible\Command\AnsiblePlaybook;
use Asm\Ansible\Command\AnsiblePlaybookInterface;
use Asm\Ansible\Exception\CommandException;
use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Process\ProcessBuilderInterface;
use Symfony\Component\Process\Process;

/**
 * Ansible command factory
 *
 * @package Asm\Ansible
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class Ansible
{

    const DEFAULT_TIMEOUT = 300;

    /**
     * @var string
     */
    private $playbookCommand;

    /**
     * @var string
     */
    private $galaxyCommand;

    /**
     * @var string
     */
    private $ansibleBaseDir;

    /**
     * @var integer
     */
    private $timeout;

    /**
     * @param string $ansibleBaseDir base directory of ansible project structure
     * @param string $playbookCommand path to playbook executable, default ansible-playbook
     * @param string $galaxyCommand path to galaxy executable, default ansible-galaxy
     */
    public function __construct(
        string $ansibleBaseDir,
        string $playbookCommand = '',
        string $galaxyCommand = ''
    ) {
        $this->ansibleBaseDir = $this->checkDir($ansibleBaseDir);
        $this->playbookCommand = $this->checkCommand($playbookCommand, 'ansible-playbook');
        $this->galaxyCommand = $this->checkCommand($galaxyCommand, 'ansible-galaxy');

        $this->timeout = Ansible::DEFAULT_TIMEOUT;
    }

    /**
     * AnsiblePlaybook instance creator
     *
     * @return AnsiblePlaybookInterface
     */
    public function playbook(): AnsiblePlaybookInterface
    {
        return new AnsiblePlaybook(
            $this->createProcess($this->playbookCommand)
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
            $this->createProcess($this->galaxyCommand)
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
    private function createProcess(string  $prefix): ProcessBuilderInterface
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
        if ('' === $command) {
            if (null !== shell_exec('which ' . $default)) {
                $command = $default;
            } else { // not testable without ansible installation
                throw new CommandException('No ' . $default . ' executable present in PATH!');
            }
        } else {
            if (!is_file($command)) {
                throw new CommandException('Command ' . $command . ' does not exist!');
            }
            if (!is_executable($command)) {
                throw new CommandException('Command ' . $command . ' is not executable!');
            }
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
}

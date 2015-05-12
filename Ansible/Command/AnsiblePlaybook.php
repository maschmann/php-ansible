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

/**
 * Class AnsiblePlaybook
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class AnsiblePlaybook extends AbstractAnsibleCommand implements AnsiblePlaybookInterface
{


   /**
     * Executes a command process
     *
     * @return stdout|stderr
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }

    /**
     * Ask for SSH password.
     *
     * @return $this
     */
    public function askPass()
    {
        // TODO: Implement askPass() method.
    }

    /**
     * Ask for su password.
     *
     * @return $this
     */
    public function askSuPass()
    {
        // TODO: Implement askSuPass() method.
    }

    /**
     * Ask for sudo password.
     *
     * @return $this
     */
    public function askSudoPass()
    {
        // TODO: Implement askSudoPass() method.
    }

    /**
     * Ask for vault password.
     *
     * @return $this
     */
    public function askVaultPass()
    {
        // TODO: Implement askVaultPass() method.
    }

    /**
     * Don't make any changes; instead, try to predict some of the changes that may occur.
     *
     * @return $this
     */
    public function check()
    {
        // TODO: Implement check() method.
    }

    /**
     * Connection type to use (default=smart).
     *
     * @param string $connection
     * @return $this
     */
    public function connection($connection = 'smart')
    {
        // TODO: Implement connection() method.
    }

    /**
     * When changing (small) files and templates, show the
     * differences in those files; works great with --check.
     *
     * @return $this
     */
    public function diff()
    {
        // TODO: Implement diff() method.
    }

    /**
     * Set additional variables as array [ 'key' => 'value' ].
     *
     * @param array $extraVars
     * @return $this
     */
    public function extraVars($extraVars = [])
    {
        // TODO: Implement extraVars() method.
    }

    /**
     * Run handlers even if a task fails.
     *
     * @return $this
     */
    public function forceHandlers()
    {
        // TODO: Implement forceHandlers() method.
    }

    /**
     * Specify number of parallel processes to use (default=5).
     *
     * @param int $forks
     * @return $this
     */
    public function forks($forks = 5)
    {
        // TODO: Implement forks() method.
    }

    /**
     * Show help message and exit.
     *
     * @return $this
     */
    public function help()
    {
        // TODO: Implement help() method.
    }

    /**
     * Specify inventory host file (default=/etc/ansible/hosts).
     *
     * @param string $inventory filename for hosts file
     * @return $this
     */
    public function inventoryFile($inventory = '/etc/ansible/hosts')
    {
        // TODO: Implement inventoryFile() method.
    }

    /**
     * Further limit selected hosts to an additional pattern.
     *
     * @param array $subset list of hosts
     * @return $this
     */
    public function limit($subset = [])
    {
        // TODO: Implement limit() method.
    }

    /**
     * Outputs a list of matching hosts; does not execute anything else.
     *
     * @return $this
     */
    public function listHosts()
    {
        // TODO: Implement listHosts() method.
    }

    /**
     * List all tasks that would be executed.
     *
     * @return $this
     */
    public function listTasks()
    {
        // TODO: Implement listTasks() method.
    }

    /**
     * Specify path(s) to module library (default=/usr/share/ansible/).
     *
     * @param array $path list of paths for modules
     * @return $this
     */
    public function modulePath($path = ['/usr/share/ansible/'])
    {
        // TODO: Implement modulePath() method.
    }

    /**
     * Disable cowsay
     *
     * @return $this
     */
    public function noCows()
    {
        // TODO: Implement noCows() method.
    }

    /**
     * Use this file to authenticate the connection.
     *
     * @param string $file private key file
     * @return $this
     */
    public function privateKey($file)
    {
        // TODO: Implement privateKey() method.
    }

    /**
     * Only run plays and tasks whose tags do not match these values.
     *
     * @param array $tags list of tags to skip
     * @return $this
     */
    public function skipTags($tags = [])
    {
        // TODO: Implement skipTags() method.
    }

    /**
     * Start the playbook at the task matching this name.
     *
     * @param string $task name of task
     * @return $this
     */
    public function startAtTask($task)
    {
        // TODO: Implement startAtTask() method.
    }

    /**
     * One-step-at-a-time: confirm each task before running.
     *
     * @return $this
     */
    public function step()
    {
        // TODO: Implement step() method.
    }

    /**
     * Run operations with su.
     *
     * @return $this
     */
    public function su()
    {
        // TODO: Implement su() method.
    }

    /**
     * Run operations with su as this user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function suUser($user = 'root')
    {
        // TODO: Implement suUser() method.
    }

    /**
     * Run operations with sudo (nopasswd).
     *
     * @return $this
     */
    public function sudo()
    {
        // TODO: Implement sudo() method.
    }

    /**
     * Desired sudo user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function sudoUser($user = 'root')
    {
        // TODO: Implement sudoUser() method.
    }

    /**
     * Perform a syntax check on the playbook, but do not execute it.
     *
     * @return $this
     */
    public function syntaxCheck()
    {
        // TODO: Implement syntaxCheck() method.
    }

    /**
     * Only run plays and tasks tagged with these values.
     *
     * @param array $tags list of tags
     * @return $this
     */
    public function tags($tags = [])
    {
        // TODO: Implement tags() method.
    }

    /**
     * Override the SSH timeout in seconds (default=10).
     *
     * @param int $timeout
     * @return $this
     */
    public function timeout($timeout = 10)
    {
        // TODO: Implement timeout() method.
    }

    /**
     * Connect as this user.
     *
     * @param string $user
     * @return $this
     */
    public function user($user)
    {
        // TODO: Implement user() method.
    }

    /**
     * Vault password file.
     *
     * @param string $file
     * @return $this
     */
    public function vaultPasswordFile($file)
    {
        // TODO: Implement vaultPasswordFile() method.
    }

    /**
     * Verbose mode (vvv for more, vvvv to enable connection debugging).
     *
     * @param string $verbose
     * @return $this
     */
    public function verbose($verbose = 'v')
    {
        // TODO: Implement verbose() method.
    }

    /**
     * Show program's version number and exit.
     *
     * @return $this
     */
    public function version()
    {
        // TODO: Implement version() method.
    }
}

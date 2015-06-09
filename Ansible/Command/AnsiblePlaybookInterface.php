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
 * Interface AnsiblePlaybookInterface
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface AnsiblePlaybookInterface extends AnsibleCommandInterface
{
    /**
     * The play to be executed.
     *
     * @param string $playbook
     * @return $this
     */
    public function play($playbook);

    /**
     * Ask for SSH password.
     *
     * @return $this
     */
    public function askPass();

    /**
     * Ask for su password.
     *
     * @return $this
     */
    public function askSuPass();

    /**
     * Ask for sudo password.
     *
     * @return $this
     */
    public function askSudoPass();

    /**
     * Ask for vault password.
     *
     * @return $this
     */
    public function askVaultPass();

    /**
     * Don't make any changes; instead, try to predict some of the changes that may occur.
     *
     * @return $this
     */
    public function check();

    /**
     * Connection type to use (default=smart).
     *
     * @param string $connection
     * @return $this
     */
    public function connection($connection = 'smart');

    /**
     * When changing (small) files and templates, show the
     * differences in those files; works great with --check.
     *
     * @return $this
     */
    public function diff();

    /**
     * Set additional variables as array [ 'key' => 'value' ] or string.
     *
     * @param string|array $extraVars
     * @return $this
     */
    public function extraVars($extraVars = '');

    /**
     * Run handlers even if a task fails.
     *
     * @return $this
     */
    public function forceHandlers();

    /**
     * Specify number of parallel processes to use (default=5).
     *
     * @param int $forks
     * @return $this
     */
    public function forks($forks = 5);

    /**
     * Show help message and exit.
     *
     * @return $this
     */
    public function help();

    /**
     * Specify inventory host file (default=/etc/ansible/hosts).
     *
     * @param string $inventory filename for hosts file
     * @return $this
     */
    public function inventoryFile($inventory = '/etc/ansible/hosts');

    /**
     * Further limit selected hosts to an additional pattern.
     *
     * @param rray|string $subset list of hosts
     * @return $this
     */
    public function limit($subset = '');

    /**
     * Outputs a list of matching hosts; does not execute anything else.
     *
     * @return $this
     */
    public function listHosts();

    /**
     * List all tasks that would be executed.
     *
     * @return $this
     */
    public function listTasks();

    /**
     * Specify path(s) to module library (default=/usr/share/ansible/).
     *
     * @param array $path list of paths for modules
     * @return $this
     */
    public function modulePath($path = ['/usr/share/ansible/']);

    /**
     * Disable cowsay
     *
     * @return $this
     */
    public function noCows();

    /**
     * Use this file to authenticate the connection.
     *
     * @param string $file private key file
     * @return $this
     */
    public function privateKey($file);

    /**
     * Only run plays and tasks whose tags do not match these values.
     *
     * @param array|string $tags list of tags to skip
     * @return $this
     */
    public function skipTags($tags = '');

    /**
     * Start the playbook at the task matching this name.
     *
     * @param string $task name of task
     * @return $this
     */
    public function startAtTask($task);

    /**
     * One-step-at-a-time: confirm each task before running.
     *
     * @return $this
     */
    public function step();

    /**
     * Run operations with su.
     *
     * @return $this
     */
    public function su();

    /**
     * Run operations with su as this user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function suUser($user = 'root');

    /**
     * Run operations with sudo (nopasswd).
     *
     * @return $this
     */
    public function sudo();

    /**
     * Desired sudo user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function sudoUser($user = 'root');

    /**
     * Perform a syntax check on the playbook, but do not execute it.
     *
     * @return $this
     */
    public function syntaxCheck();

    /**
     * Only run plays and tasks tagged with these values.
     *
     * @param string|array $tags list of tags
     * @return $this
     */
    public function tags($tags);

    /**
     * Override the SSH timeout in seconds (default=10).
     *
     * @param int $timeout
     * @return $this
     */
    public function timeout($timeout = 10);

    /**
     * Connect as this user.
     *
     * @param string $user
     * @return $this
     */
    public function user($user);

    /**
     * Vault password file.
     *
     * @param string $file
     * @return $this
     */
    public function vaultPasswordFile($file);

    /**
     * Verbose mode (vvv for more, vvvv to enable connection debugging).
     *
     * @param string $verbose
     * @return $this
     */
    public function verbose($verbose = 'v');

    /**
     * Show program's version number and exit.
     *
     * @return $this
     */
    public function version();
}

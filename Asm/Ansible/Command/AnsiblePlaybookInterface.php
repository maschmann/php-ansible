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
     * @return AnsiblePlaybookInterface
     */
    public function play(string $playbook): AnsiblePlaybookInterface;

    /**
     * Ask for SSH password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askPass(): AnsiblePlaybookInterface;

    /**
     * Ask for su password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askSuPass(): AnsiblePlaybookInterface;

    /**
     * Ask for sudo password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askBecomePass(): AnsiblePlaybookInterface;

    /**
     * Ask for vault password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askVaultPass(): AnsiblePlaybookInterface;

    /**
     * Enable privilege escalation
     *
     * @return AnsiblePlaybookInterface
     * @see http://docs.ansible.com/ansible/become.html
     */
    public function become(): AnsiblePlaybookInterface;

    /**
     * Desired become user (default=root).
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function becomeUser(string $user = 'root'): AnsiblePlaybookInterface;

    /**
     * Don't make any changes; instead, try to predict some of the changes that may occur.
     *
     * @return AnsiblePlaybookInterface
     */
    public function check(): AnsiblePlaybookInterface;

    /**
     * Connection type to use (default=smart).
     *
     * @param string $connection
     * @return AnsiblePlaybookInterface
     */
    public function connection(string $connection = 'smart'): AnsiblePlaybookInterface;

    /**
     * When changing (small) files and templates, show the
     * differences in those files; works great with --check.
     *
     * @return AnsiblePlaybookInterface
     */
    public function diff(): AnsiblePlaybookInterface;

    /**
     * Sends extra variables to Ansible. The $extraVars parameter can be one of the following.
     *
     * ## Array
     * If an array is passed, it must contain the [ 'key' => 'value' ] pairs of the variables.
     *
     * Example:
     * ```php
     * $ansible = new Ansible()->playbook()->extraVars(['path' => 'some/path']);
     * ```
     *
     * ## File
     * As Ansible also supports extra vars loaded from an YML file, you can also pass a file path.
     *
     * Example:
     * ```php
     * $ansible = new Ansible()->playbook()->extraVars('/path/to/extra/vars.yml');
     * ```
     *
     * ## String
     * You can also pass the raw extra vars string directly.
     *
     * Example:
     * ```php
     * $ansible = new Ansible()->playbook()->extraVars('path=/some/path');
     * ```
     * @param string|array $extraVars
     * @return AnsiblePlaybookInterface
     */
    public function extraVars($extraVars = ''): AnsiblePlaybookInterface;

    /**
     * clear the fact cache
     *
     * @return AnsiblePlaybookInterface
     */
    public function flushCache(): AnsiblePlaybookInterface;

    /**
     * Run handlers even if a task fails.
     *
     * @return AnsiblePlaybookInterface
     */
    public function forceHandlers(): AnsiblePlaybookInterface;

    /**
     * Specify number of parallel processes to use (default=5).
     *
     * @param int $forks
     * @return AnsiblePlaybookInterface
     */
    public function forks(int $forks = 5): AnsiblePlaybookInterface;

    /**
     * Show help message and exit.
     *
     * @return AnsiblePlaybookInterface
     */
    public function help(): AnsiblePlaybookInterface;

    /**
     * Specify inventory host file (default=/etc/ansible/hosts).
     *
     * @param string $inventory filename for hosts file
     * @return AnsiblePlaybookInterface
     */
    public function inventoryFile(string $inventory = '/etc/ansible/hosts'): AnsiblePlaybookInterface;

    /**
     * Specify inventory host list manually.
     * Example:
     *
     * ```php
     * $ansible = new Ansible()->playbook()->inventory(['localhost', 'host1.example.com']);
     * ```
     *
     * @param array $hosts An array containing the list of hosts.
     * @return AnsiblePlaybookInterface
     */
    public function inventory(array $hosts = []): AnsiblePlaybookInterface;

    /**
     * Further limit selected hosts to an additional pattern.
     *
     * @param array|string $subset list of hosts
     * @return AnsiblePlaybookInterface
     */
    public function limit($subset = ''): AnsiblePlaybookInterface;

    /**
     * Outputs a list of matching hosts; does not execute anything else.
     *
     * @return AnsiblePlaybookInterface
     */
    public function listHosts(): AnsiblePlaybookInterface;

    /**
     * List all tasks that would be executed.
     *
     * @return AnsiblePlaybookInterface
     */
    public function listTasks(): AnsiblePlaybookInterface;

    /**
     * Specify path(s) to module library (default=/usr/share/ansible/).
     *
     * @param array $path list of paths for modules
     * @return AnsiblePlaybookInterface
     */
    public function modulePath(array $path = ['/usr/share/ansible/']): AnsiblePlaybookInterface;

    /**
     * the new vault identity to use for rekey
     *
     * @param string $vaultId
     * @return AnsiblePlaybookInterface
     */
    public function newVaultId(string $vaultId): AnsiblePlaybookInterface;

    /**
     * new vault password file for rekey
     *
     * @param string $passwordFile
     * @return AnsiblePlaybookInterface
     */
    public function newVaultPasswordFile(string $passwordFile): AnsiblePlaybookInterface;

    /**
     * Disable cowsay
     *
     * @return AnsiblePlaybookInterface
     */
    public function noCows(): AnsiblePlaybookInterface;

    /**
     * Disable console colors
     *
     * @param bool $colors
     * @return AnsiblePlaybookInterface
     */
    public function colors(bool $colors = true): AnsiblePlaybookInterface;

    /**
     * Use this file to authenticate the connection.
     *
     * @param string $file private key file
     * @return AnsiblePlaybookInterface
     */
    public function privateKey(string $file): AnsiblePlaybookInterface;

    /**
     * Only run plays and tasks whose tags do not match these values.
     *
     * @param array|string $tags list of tags to skip
     * @return AnsiblePlaybookInterface
     */
    public function skipTags($tags = ''): AnsiblePlaybookInterface;

    /**
     * Start the playbook at the task matching this name.
     *
     * @param string $task name of task
     * @return AnsiblePlaybookInterface
     */
    public function startAtTask(string $task): AnsiblePlaybookInterface;

    /**
     * One-step-at-a-time: confirm each task before running.
     *
     * @return AnsiblePlaybookInterface
     */
    public function step(): AnsiblePlaybookInterface;

    /**
     * Run operations with su.
     *
     * @return AnsiblePlaybookInterface
     */
    public function su(): AnsiblePlaybookInterface;

    /**
     * Run operations with su as this user (default=root).
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function suUser(string $user = 'root'): AnsiblePlaybookInterface;

    /**
     * specify extra arguments to pass to scp only (e.g. -l)
     *
     * @param string|array $scpExtraArgs
     * @return AnsiblePlaybookInterface
     */
    public function scpExtraArgs($scpExtraArgs): AnsiblePlaybookInterface;

    /**
     * specify extra arguments to pass to sftp only (e.g. -f, -l)
     *
     * @param string|array $sftpExtraArgs
     * @return AnsiblePlaybookInterface
     */
    public function sftpExtraArgs($sftpExtraArgs): AnsiblePlaybookInterface;

    /**
     * specify common arguments to pass to sftp/scp/ssh (e.g. ProxyCommand)
     *
     * @param string|array $sshArgs
     * @return AnsiblePlaybookInterface
     */
    public function sshCommonArgs($sshArgs): AnsiblePlaybookInterface;

    /**
     * specify extra arguments to pass to ssh only (e.g. -R)
     *
     * @param string|array $extraArgs
     * @return AnsiblePlaybookInterface
     */
    public function sshExtraArgs($extraArgs): AnsiblePlaybookInterface;

    /**
     * Perform a syntax check on the playbook, but do not execute it.
     *
     * @return AnsiblePlaybookInterface
     */
    public function syntaxCheck(): AnsiblePlaybookInterface;

    /**
     * Only run plays and tasks tagged with these values.
     *
     * @param string|array $tags list of tags
     * @return AnsiblePlaybookInterface
     */
    public function tags($tags): AnsiblePlaybookInterface;

    /**
     * Override the SSH timeout in seconds (default=10).
     *
     * @param int $timeout
     * @return AnsiblePlaybookInterface
     */
    public function timeout(int $timeout = 10): AnsiblePlaybookInterface;

    /**
     * Connect as this user.
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function user(string $user): AnsiblePlaybookInterface;

    /**
     * the vault identity to use
     *
     * @param string $vaultId
     * @return AnsiblePlaybookInterface
     */
    public function vaultId(string $vaultId): AnsiblePlaybookInterface;

    /**
     * Vault password file.
     *
     * @param string $file
     * @return AnsiblePlaybookInterface
     */
    public function vaultPasswordFile(string $file): AnsiblePlaybookInterface;

    /**
     * Verbose mode (vvv for more, vvvv to enable connection debugging).
     *
     * @param string $verbose
     * @return AnsiblePlaybookInterface
     */
    public function verbose(string $verbose = 'v'): AnsiblePlaybookInterface;

    /**
     * Show program's version number and exit.
     *
     * @return AnsiblePlaybookInterface
     */
    public function version(): AnsiblePlaybookInterface;
}

<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

use Asm\Ansible\Utils\Str;
use InvalidArgumentException;

/**
 * Class AnsiblePlaybook
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class AnsiblePlaybook extends AbstractAnsibleCommand implements AnsiblePlaybookInterface
{
    private bool $hasInventory = false;

    /**
     * Executes a command process.
     * Returns either exit code or string output if no callback is given.
     *
     * @param callable|null $callback
     * @return integer|string
     */
    public function execute(?callable $callback = null): int|string
    {
        $this->checkInventory();

        return $this->runProcess($callback);
    }

    /**
     * The play to be executed.
     *
     * @param string $playbook
     * @return AnsiblePlaybookInterface
     */
    public function play(string $playbook): AnsiblePlaybookInterface
    {
        $this->addBaseoption($playbook);

        return $this;
    }

    /**
     * Ask for SSH password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askPass(): AnsiblePlaybookInterface
    {
        $this->addParameter('--ask-pass');

        return $this;
    }

    /**
     * Ask for su password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askSuPass(): AnsiblePlaybookInterface
    {
        $this->addParameter('--ask-su-pass');

        return $this;
    }

    /**
     * Ask for sudo password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askBecomePass(): AnsiblePlaybookInterface
    {
        $this->addParameter('--ask-become-pass');

        return $this;
    }

    /**
     * Ask for vault password.
     *
     * @return AnsiblePlaybookInterface
     */
    public function askVaultPass(): AnsiblePlaybookInterface
    {
        $this->addParameter('--ask-vault-pass');

        return $this;
    }

    /**
     * Enable privilege escalation
     *
     * @return AnsiblePlaybookInterface
     * @see http://docs.ansible.com/ansible/become.html
     */
    public function become(): AnsiblePlaybookInterface
    {
        $this->addParameter('--become');

        return $this;
    }

    /**
     * Desired sudo user (default=root).
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function becomeUser(string $user = 'root'): AnsiblePlaybookInterface
    {
        $this->addOption('--become-user', $user);

        return $this;
    }

    /**
     * Don't make any changes; instead, try to predict some of the changes that may occur.
     *
     * @return AnsiblePlaybookInterface
     */
    public function check(): AnsiblePlaybookInterface
    {
        $this->addParameter('--check');

        return $this;
    }

    /**
     * Connection type to use (default=smart).
     *
     * @param string $connection
     * @return AnsiblePlaybookInterface
     */
    public function connection(string $connection = 'smart'): AnsiblePlaybookInterface
    {
        $this->addOption('--connection', $connection);

        return $this;
    }

    /**
     * When changing (small) files and templates, show the
     * differences in those files; works great with --check.
     *
     * @return AnsiblePlaybookInterface
     */
    public function diff(): AnsiblePlaybookInterface
    {
        $this->addParameter('--diff');

        return $this;
    }

    /**
     * Example:
     * ```php
     * $ansible = new Ansible()->extraVars('path=/some/path');
     * ```
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
     *
     * ## JSON String
     * You can also use a JSON string to pass the key/value pairs of extra vars.
     *
     * Example:
     * ```php
     * $ansible = new Ansible()->playbook()->extraVars('{ "key1": "value1" }');
     * ```
     *
     * @param string|array $extraVars
     * @return AnsiblePlaybookInterface
     */
    public function extraVars(string|array $extraVars = ''): AnsiblePlaybookInterface
    {
        if (empty($extraVars)) {
            return $this;
        }

        // Building the key=>value parameter
        if (is_array($extraVars)) {
            $vars = [];
            foreach ($extraVars as $key => $value) {
                $vars[] = sprintf('%s=%s', $key, $value);
            }
            $this->addOption('--extra-vars', implode(' ', $vars));
            return $this;
        }

        // At this point, the only allowed type is string.
        if (!is_string($extraVars)) {
            throw new InvalidArgumentException(sprintf('Expected string|array, got "%s"', gettype($extraVars)));
        }

        // Trim the string & check if empty before moving on.
        $extraVars = trim($extraVars);

        if ($extraVars === '') {
            return $this;
        }

        // JSON formatted string can be used for extra vars as is.
        // The value is automatically escaped & wrapped around single quotes from the Library's process.
        if (Str::isJsonFormatted($extraVars)) {
            $this->addOption('--extra-vars', $extraVars);

            return $this;
        }

        if (!str_contains($extraVars, '=')) {
            throw new InvalidArgumentException('The extra vars raw string should be in the "key=value" form.');
        }

        $this->addOption('--extra-vars', $extraVars);
        return $this;
    }

    /**
     * Run handlers even if a task fails.
     *
     * @return AnsiblePlaybookInterface
     */
    public function forceHandlers(): AnsiblePlaybookInterface
    {
        $this->addParameter('--force-handlers');

        return $this;
    }

    /**
     * Specify number of parallel processes to use (default=5).
     *
     * @param int $forks
     * @return AnsiblePlaybookInterface
     */
    public function forks(int $forks = 5): AnsiblePlaybookInterface
    {
        // this is ugly, but on commandline it will later be automatically string anyways
        $this->addOption('--forks', (string)$forks);

        return $this;
    }

    /**
     * Show help message and exit.
     *
     * @return AnsiblePlaybookInterface
     */
    public function help(): AnsiblePlaybookInterface
    {
        $this->addParameter('--help');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function inventory(array $hosts = []): AnsiblePlaybookInterface
    {
        if (empty($hosts)) {
            return $this;
        }

        // In order to let ansible-playbook understand that the given option is a list of hosts, the list must end by
        // comma "," if it contains just an entry. For example, supposing just a single host, "localhosts":
        //
        //   Wrong: --inventory="locahost"
        // Correct: --inventory="locahost,"
        $hostList = implode(', ', $hosts);

        if (count($hosts) === 1) {
            $hostList .= ',';
        }

        $this->addOption('--inventory', sprintf('"%s"', $hostList));
        $this->hasInventory = true;

        return $this;
    }

    /**
     * Specify inventory host file (default=/etc/ansible/hosts).
     *
     * @param string $inventory filename for hosts file
     * @return AnsiblePlaybookInterface
     */
    public function inventoryFile(string $inventory = '/etc/ansible/hosts'): AnsiblePlaybookInterface
    {
        $this->addOption('--inventory-file', $inventory);
        $this->hasInventory = true;

        return $this;
    }

    /**
     * Further limit selected hosts to an additional pattern.
     *
     * @param string|array $subset list of hosts
     * @return AnsiblePlaybookInterface
     */
    public function limit(string|array $subset = ''): AnsiblePlaybookInterface
    {
        $subset = $this->checkParam($subset, ',');
        $this->addOption('--limit', $subset);

        return $this;
    }

    /**
     * Outputs a list of matching hosts; does not execute anything else.
     *
     * @return AnsiblePlaybookInterface
     */
    public function listHosts(): AnsiblePlaybookInterface
    {
        $this->addParameter('--list-hosts');

        return $this;
    }

    /**
     * List all tasks that would be executed.
     *
     * @return AnsiblePlaybookInterface
     */
    public function listTasks(): AnsiblePlaybookInterface
    {
        $this->addParameter('--list-tasks');

        return $this;
    }

    /**
     * Specify path(s) to module library (default=/usr/share/ansible/).
     *
     * @param array $path list of paths for modules
     * @return AnsiblePlaybookInterface
     */
    public function modulePath(array $path = ['/usr/share/ansible/']): AnsiblePlaybookInterface
    {
        $this->addOption('--module-path', implode(',', $path));

        return $this;
    }

    /**
     * Disable cowsay
     *
     * @codeCoverageIgnore
     * @return AnsiblePlaybookInterface
     */
    public function noCows(): AnsiblePlaybookInterface
    {
        $this->processBuilder->setEnv('ANSIBLE_NOCOWS', 1);

        return $this;
    }

    /**
     * Enable/Disable Colors
     *
     * @param bool $colors
     * @return AnsiblePlaybookInterface
     */
    public function colors(bool $colors = true): AnsiblePlaybookInterface
    {
        $this->processBuilder->setEnv('ANSIBLE_FORCE_COLOR', intval($colors));

        return $this;
    }

    /**
     * Enable/Disable Json Output
     *
     * @return AnsiblePlaybookInterface
     */
    public function json(): AnsiblePlaybookInterface
    {
        $this->processBuilder->setEnv('ANSIBLE_STDOUT_CALLBACK', 'json');

        return $this;
    }

    /**
     * Use this file to authenticate the connection.
     *
     * @param string $file private key file
     * @return AnsiblePlaybookInterface
     */
    public function privateKey(string $file): AnsiblePlaybookInterface
    {
        $this->addOption('--private-key', $file);

        return $this;
    }

    /**
     * Only run plays and tasks whose tags do not match these values.
     *
     * @param string|array $tags list of tags to skip
     * @return AnsiblePlaybookInterface
     */
    public function skipTags(string|array $tags = ''): AnsiblePlaybookInterface
    {
        $tags = $this->checkParam($tags, ',');
        $this->addOption('--skip-tags', $tags);

        return $this;
    }

    /**
     * Start the playbook at the task matching this name.
     *
     * @param string $task name of task
     * @return AnsiblePlaybookInterface
     */
    public function startAtTask(string $task): AnsiblePlaybookInterface
    {
        $this->addOption('--start-at-task', $task);

        return $this;
    }

    /**
     * One-step-at-a-time: confirm each task before running.
     *
     * @return AnsiblePlaybookInterface
     */
    public function step(): AnsiblePlaybookInterface
    {
        $this->addParameter('--step');

        return $this;
    }

    /**
     * Run operations with su.
     *
     * @return AnsiblePlaybookInterface
     */
    public function su(): AnsiblePlaybookInterface
    {
        $this->addParameter('--su');

        return $this;
    }

    /**
     * Run operations with su as this user (default=root).
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function suUser(string $user = 'root'): AnsiblePlaybookInterface
    {
        $this->addOption('--su-user', $user);

        return $this;
    }

    /**
     * Perform a syntax check on the playbook, but do not execute it.
     *
     * @return AnsiblePlaybookInterface
     */
    public function syntaxCheck(): AnsiblePlaybookInterface
    {
        $this->addParameter('--syntax-check');

        return $this;
    }

    /**
     * Only run plays and tasks tagged with these values.
     *
     * @param string|array $tags list of tags
     * @return AnsiblePlaybookInterface
     */
    public function tags(string|array $tags): AnsiblePlaybookInterface
    {
        $tags = $this->checkParam($tags, ',');
        $this->addOption('--tags', $tags);

        return $this;
    }

    /**
     * Override the SSH timeout in seconds (default=10).
     *
     * @param int $timeout
     * @return AnsiblePlaybookInterface
     */
    public function timeout(int $timeout = 10): AnsiblePlaybookInterface
    {
        $this->addOption('--timeout', $timeout);

        return $this;
    }

    /**
     * Connect as this user.
     *
     * @param string $user
     * @return AnsiblePlaybookInterface
     */
    public function user(string $user): AnsiblePlaybookInterface
    {
        $this->addOption('--user', $user);

        return $this;
    }

    /**
     * Vault password file.
     *
     * @param string $file
     * @return AnsiblePlaybookInterface
     */
    public function vaultPasswordFile(string $file): AnsiblePlaybookInterface
    {
        $this->addoption('--vault-password-file', $file);

        return $this;
    }

    /**
     * Verbose mode (vvv for more, vvvv to enable connection debugging).
     *
     * @param string $verbose
     * @return AnsiblePlaybookInterface
     */
    public function verbose(string $verbose = 'v'): AnsiblePlaybookInterface
    {
        $this->addParameter('-' . $verbose);

        return $this;
    }

    /**
     * Show program's version number and exit.
     *
     * @return AnsiblePlaybookInterface
     */
    public function version(): AnsiblePlaybookInterface
    {
        $this->addParameter('--version');

        return $this;
    }

    /**
     * clear the fact cache
     *
     * @return AnsiblePlaybookInterface
     */
    public function flushCache(): AnsiblePlaybookInterface
    {
        $this->addParameter('--flush-cache');

        return $this;
    }

    /**
     * the new vault identity to use for rekey
     *
     * @param string $vaultId
     * @return AnsiblePlaybookInterface
     */
    public function newVaultId(string $vaultId): AnsiblePlaybookInterface
    {
        $this->addOption('--new-vault-id', $vaultId);

        return $this;
    }

    /**
     * new vault password file for rekey
     *
     * @param string $passwordFile
     * @return AnsiblePlaybookInterface
     */
    public function newVaultPasswordFile(string $passwordFile): AnsiblePlaybookInterface
    {
        $this->addOption('--new-vault-password-file', $passwordFile);

        return $this;
    }

    /**
     * specify extra arguments to pass to scp only (e.g. -l)
     *
     * @param string|array $scpExtraArgs
     * @return AnsiblePlaybookInterface
     */
    public function scpExtraArgs(string|array $scpExtraArgs): AnsiblePlaybookInterface
    {
        $scpExtraArgs = $this->checkParam($scpExtraArgs, ',');
        $this->addOption('--scp-extra-args', $scpExtraArgs);

        return $this;
    }

    /**
     * specify extra arguments to pass to sftp only (e.g. -f, -l)
     *
     * @param string|array $sftpExtraArgs
     * @return AnsiblePlaybookInterface
     */
    public function sftpExtraArgs(string|array $sftpExtraArgs): AnsiblePlaybookInterface
    {
        $sftpExtraArgs = $this->checkParam($sftpExtraArgs, ',');
        $this->addOption('--sftp-extra-args', $sftpExtraArgs);

        return $this;
    }

    /**
     * specify common arguments to pass to sftp/scp/ssh (e.g. ProxyCommand)
     *
     * @param string|array $sshArgs
     * @return AnsiblePlaybookInterface
     */
    public function sshCommonArgs(string|array $sshArgs): AnsiblePlaybookInterface
    {
        $sshArgs = $this->checkParam($sshArgs, ',');
        $this->addOption('--ssh-common-args', $sshArgs);

        return $this;
    }

    /**
     * specify extra arguments to pass to ssh only (e.g. -R)
     *
     * @param string|array $extraArgs
     * @return AnsiblePlaybookInterface
     */
    public function sshExtraArgs(string|array $extraArgs): AnsiblePlaybookInterface
    {
        $extraArgs = $this->checkParam($extraArgs, ',');
        $this->addOption('--ssh-extra-args', $extraArgs);

        return $this;
    }

    /**
     * the vault identity to use
     *
     * @param string $vaultId
     * @return AnsiblePlaybookInterface
     */
    public function vaultId(string $vaultId): AnsiblePlaybookInterface
    {
        $this->addOption('--vault-id', $vaultId);

        return $this;
    }

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    public function getCommandlineArguments(bool $asArray = true): string|array
    {
        $this->checkInventory();

        return $this->prepareArguments($asArray);
    }

    /**
     * @inheritDoc
     */
    public function rolesPath(string $path): AnsiblePlaybookInterface
    {
        if (empty($path)) {
            return $this;
        }

        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The path "%s" does not exist.', $path));
        }

        $this->processBuilder->setEnv('ANSIBLE_ROLES_PATH', $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hostKeyChecking(bool $enable = true): AnsiblePlaybookInterface
    {
        $enable ? $flag = 'True' : $flag = 'False';

        $this->processBuilder->setEnv('ANSIBLE_HOST_KEY_CHECKING', $flag);
        return $this;
    }

    /**
     * Ansible SSH pipelining option
     * https://docs.ansible.com/ansible/latest/reference_appendices/config.html#ansible-pipelining
     *
     * @param bool $enable
     * @return AnsiblePlaybookInterface
     **/
    public function sshPipelining(bool $enable = false): AnsiblePlaybookInterface
    {
        $enable ?
            $flag = 'True' :
            $flag = 'False';

        $this->processBuilder->setEnv('ANSIBLE_SSH_PIPELINING', $flag);
        return $this;
    }

    /**
     * If no inventory file is given, assume
     */
    private function checkInventory(): void
    {
        if (!$this->hasInventory) {
            $inventory = str_replace('.yml', '', $this->getBaseOptions());
            $this->inventoryFile($inventory);
        }
    }
}

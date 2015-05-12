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
     * @var string
     */
    private $playbook;

    /**
     * @var boolean
     */
    private $hasInventory = false;

    /**
     * Executes a command process
     *
     * @param null $callback
     * @return stderr|stdout
     */
    public function execute($callback = null)
    {
        $this->checkInventory();

        $arguments = array_merge(
            [$this->playbook],
            $this->getOptions(),
            $this->getParameters()
        );

        $process = $this->processBuilder
            ->setArguments($arguments)
            ->getProcess();

        return $process->run($callback);
    }

    /**
     * The play to be executed.
     *
     * @param string $playbook
     * @return $this
     */
    public function play($playbook)
    {
        $this->playbook = $playbook;

        return $this;
    }

    /**
     * Ask for SSH password.
     *
     * @return $this
     */
    public function askPass()
    {
        $this->addParameter('--ask-pass');

        return $this;
    }

    /**
     * Ask for su password.
     *
     * @return $this
     */
    public function askSuPass()
    {
        $this->addParameter('--ask-su-pass');

        return $this;
    }

    /**
     * Ask for sudo password.
     *
     * @return $this
     */
    public function askSudoPass()
    {
        $this->addParameter('--ask-sudo-pass');

        return $this;
    }

    /**
     * Ask for vault password.
     *
     * @return $this
     */
    public function askVaultPass()
    {
        $this->addParameter('--ask-vault-pass');

        return $this;
    }

    /**
     * Don't make any changes; instead, try to predict some of the changes that may occur.
     *
     * @return $this
     */
    public function check()
    {
        $this->addParameter('--check');

        return $this;
    }

    /**
     * Connection type to use (default=smart).
     *
     * @param string $connection
     * @return $this
     */
    public function connection($connection = 'smart')
    {
        $this->addOption('--connection', $connection);

        return $this;
    }

    /**
     * When changing (small) files and templates, show the
     * differences in those files; works great with --check.
     *
     * @return $this
     */
    public function diff()
    {
        $this->addParameter('--diff');

        return $this;
    }

    /**
     * Set additional variables as array [ 'key' => 'value' ].
     *
     * @param array $extraVars
     * @return $this
     */
    public function extraVars($extraVars = [])
    {
        $this->addOption('--extra-vars', implode(',', $extraVars));

        return $this;
    }

    /**
     * Run handlers even if a task fails.
     *
     * @return $this
     */
    public function forceHandlers()
    {
        $this->addParameter('--force-handlers');

        return $this;
    }

    /**
     * Specify number of parallel processes to use (default=5).
     *
     * @param int $forks
     * @return $this
     */
    public function forks($forks = 5)
    {
        $this->addOption('--forks', $forks);

        return $this;
    }

    /**
     * Show help message and exit.
     *
     * @return $this
     */
    public function help()
    {
        $this->addParameter('--help');

        return $this;
    }

    /**
     * Specify inventory host file (default=/etc/ansible/hosts).
     *
     * @param string $inventory filename for hosts file
     * @return $this
     */
    public function inventoryFile($inventory = '/etc/ansible/hosts')
    {
        $this->addOption('--inventory-file', $inventory);
        $this->hasInventory = true;

        return $this;
    }

    /**
     * Further limit selected hosts to an additional pattern.
     *
     * @param array|string $subset list of hosts
     * @return $this
     */
    public function limit($subset = '')
    {
        if (is_array($subset)) {
            $subset = implode(',', $subset);
        }

        $this->addOption('--limit', $subset);

        return $this;
    }

    /**
     * Outputs a list of matching hosts; does not execute anything else.
     *
     * @return $this
     */
    public function listHosts()
    {
        $this->addParameter('--list-hosts');

        return $this;
    }

    /**
     * List all tasks that would be executed.
     *
     * @return $this
     */
    public function listTasks()
    {
        $this->addParameter('--list-tasks');

        return $this;
    }

    /**
     * Specify path(s) to module library (default=/usr/share/ansible/).
     *
     * @param array $path list of paths for modules
     * @return $this
     */
    public function modulePath($path = ['/usr/share/ansible/'])
    {
        $this->addOption('--module-path', implode(',', $path));

        return $this;
    }

    /**
     * Disable cowsay
     *
     * @return $this
     */
    public function noCows()
    {
        $this->processBuilder->setEnv('ANSIBLE_NOCOWS', 1);

        return $this;
    }

    /**
     * Use this file to authenticate the connection.
     *
     * @param string $file private key file
     * @return $this
     */
    public function privateKey($file)
    {
        $this->addOption('--private-key', $file);

        return $this;
    }

    /**
     * Only run plays and tasks whose tags do not match these values.
     *
     * @param array|string $tags list of tags to skip
     * @return $this
     */
    public function skipTags($tags = '')
    {
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $this->addOption('--skip-tags', $tags);

        return $this;
    }

    /**
     * Start the playbook at the task matching this name.
     *
     * @param string $task name of task
     * @return $this
     */
    public function startAtTask($task)
    {
        $this->addOption('--start-at-task', $task);

        return $this;
    }

    /**
     * One-step-at-a-time: confirm each task before running.
     *
     * @return $this
     */
    public function step()
    {
        $this->addParameter('--step');

        return $this;
    }

    /**
     * Run operations with su.
     *
     * @return $this
     */
    public function su()
    {
        $this->addParameter('--su');

        return $this;
    }

    /**
     * Run operations with su as this user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function suUser($user = 'root')
    {
        $this->addOption('--su-user', $user);

        return $this;
    }

    /**
     * Run operations with sudo (nopasswd).
     *
     * @return $this
     */
    public function sudo()
    {
        $this->addParameter('--sudo');

        return $this;
    }

    /**
     * Desired sudo user (default=root).
     *
     * @param string $user
     * @return $this
     */
    public function sudoUser($user = 'root')
    {
        $this->addOptions('--sudo-user', $user);

        return $this;
    }

    /**
     * Perform a syntax check on the playbook, but do not execute it.
     *
     * @return $this
     */
    public function syntaxCheck()
    {
        $this->addParameter('--syntax-check');

        return $this;
    }

    /**
     * Only run plays and tasks tagged with these values.
     *
     * @param array $tags list of tags
     * @return $this
     */
    public function tags($tags = [])
    {
        $this->addOption('--tags', implode(',', $tags));

        return $this;
    }

    /**
     * Override the SSH timeout in seconds (default=10).
     *
     * @param int $timeout
     * @return $this
     */
    public function timeout($timeout = 10)
    {
        $this->addOption('--timeout', $timeout);

        return $this;
    }

    /**
     * Connect as this user.
     *
     * @param string $user
     * @return $this
     */
    public function user($user)
    {
        $this->addOption('--user', $user);

        return $this;
    }

    /**
     * Vault password file.
     *
     * @param string $file
     * @return $this
     */
    public function vaultPasswordFile($file)
    {
        $this->addoption('--vault-password-file', $file);

        return $this;
    }

    /**
     * Verbose mode (vvv for more, vvvv to enable connection debugging).
     *
     * @param string $verbose
     * @return $this
     */
    public function verbose($verbose = 'v')
    {
        $this->addParameter('-' . $verbose);

        return $this;
    }

    /**
     * Show program's version number and exit.
     *
     * @return $this
     */
    public function version()
    {
        $this->addParameter('--version');

        return $this;
    }

    /**
     * If no inventory file is given, assume
     */
    private function checkInventory()
    {
        if (!$this->hasInventory) {
            $inventory = str_replace('.yml', '', $this->playbook);
            $this->inventoryFile($inventory);
        }
    }
}

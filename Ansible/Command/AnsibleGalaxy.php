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
 * Class AnsibleGalaxy
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
final class AnsibleGalaxy extends AbstractAnsibleCommand implements AnsibleGalaxyInterface
{

    /**
     * Executes a command process
     *
     * @param null $callback
     * @return stderr|stdout
     */
    public function execute($callback = null)
    {
        $arguments = array_merge(
            [$this->getBaseOptions()],
            $this->getOptions(),
            $this->getParameters()
        );

        $process = $this->processBuilder
            ->setArguments($arguments)
            ->getProcess();

        // exitcode
        $result = $process->run($callback);

        // text-mode
        if (null === $callback) {
            // @codeCoverageIgnoreStart
            $result = $process->getOutput();

            if (false === $process->isSuccessful()) {
                $process->getErrorOutput();
            }
            // @codeCoverageIgnoreEnd
        }

        return $result;
    }

    /**
     * Initialize a new role with base structure.
     *
     * @param string $roleName
     * @return $this
     */
    public function init($roleName)
    {
        $this
            ->addBaseoption('init')
            ->addBaseoption($roleName);

        return $this;
    }

    /**
     * @param string $role
     * @param string $version
     * @return $this
     */
    public function info($role, $version = '')
    {
        if ('' !== $version) {
            $role = $role . ',' . $version;
        }

        $this
            ->addBaseoption('info')
            ->addBaseoption($role);

        return $this;
    }

    /**
     * Install packages.
     *
     * If you are unsure whether the role(s) is already installed,
     * either check first or use the "force" option.
     *
     * @param string|array $roles role_name(s)[,version] | scm+role_repo_url[,version]
     * @return $this
     */
    public function install($roles = '')
    {
        if (true === is_array($roles)) {
            $roles = implode(' ', $roles);
        }

        $this->addBaseoption('install');

        if ('' !== $roles) {
            $this->addBaseoption($roles);
        }

        return $this;
    }

    /**
     * Get a list of installed modules.
     *
     * @param string $roleName
     * @return string list of installed modules
     */
    public function modulelist($roleName = '')
    {
        $this->addBaseoption('list');

        if ('' !== $roleName) {
            $this->addBaseoption($roleName);
        }

        return $this;
    }

    /**
     * Add package(s)
     *
     * @param string|array $roles
     * @return $this
     */
    public function remove($roles = '')
    {
        if (true === is_array($roles)) {
            $roles = implode(' ', $roles);
        }

        $this
            ->addBaseoption('remove')
            ->addBaseoption($roles);

        return $this;
    }

    /**
     * Show general or specific help.
     *
     * @return $this
     */
    public function help()
    {
        $this->addParameter('--help');

        return $this;
    }

    /**
     * The path in which the skeleton role will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return $this
     */
    public function initPath($path = '')
    {
        $this->addOption('--init-path', $path);

        return $this;
    }

    /**
     * Don't query the galaxy API when creating roles.
     *
     * @return $this
     */
    public function offline()
    {
        $this->addParameter('--offline');

        return $this;
    }

    /**
     * The API server destination.
     *
     * @param string $apiServer
     * @return $this
     */
    public function server($apiServer)
    {
        $this->addOption('--server', $apiServer);

        return $this;
    }

    /**
     * Force overwriting an existing role.
     *
     * @return $this
     */
    public function force()
    {
        $this->addParameter('--force');

        return $this;
    }

    /**
     * A file containing a list of roles to be imported.
     *
     * @param string $roleFile FILE
     * @return $this
     */
    public function roleFile($roleFile)
    {
        $this->addOption('--role-file', $roleFile);

        return $this;
    }

    /**
     * The path to the directory containing your roles.
     *
     * The default is the roles_path configured in your
     * ansible.cfg file (/etc/ansible/roles if not configured).
     *
     * @param string $rolesPath
     * @return $this
     */
    public function rolesPath($rolesPath)
    {
        $this->addOption('--roles-path', $rolesPath);

        return $this;
    }

    /**
     * Ignore errors and continue with the next specified role.
     *
     * @return $this
     */
    public function ignoreErrors()
    {
        $this->addParameter('--ignore-errors');

        return $this;
    }

    /**
     * Don't download roles listed as dependencies.
     *
     * @return $this
     */
    public function noDeps()
    {
        $this->addParameter('--no-deps');

        return $this;
    }

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    public function getCommandlineArguments($asArray = true)
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
}

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
     * Executes a command process.
     * Returns either exitcode or string output if no callback is given.
     *
     * @param callable|null $callback
     * @return integer|string
     */
    public function execute($callback = null)
    {
        return $this->runProcess($callback);
    }

    /**
     * Initialize a new role with base structure.
     *
     * @param string $roleName
     * @return AnsibleGalaxyInterface
     */
    public function init(string $roleName): AnsibleGalaxyInterface
    {
        $this
            ->addBaseoption('init')
            ->addBaseoption($roleName);

        return $this;
    }

    /**
     * @param string $role
     * @param string $version
     * @return AnsibleGalaxyInterface
     */
    public function info(string $role, string $version = ''): AnsibleGalaxyInterface
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
     * @return AnsibleGalaxyInterface
     */
    public function install($roles = ''): AnsibleGalaxyInterface
    {
        $roles = $this->checkParam($roles, ' ');

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
     * @return AnsibleGalaxyInterface
     */
    public function modulelist(string $roleName = ''): AnsibleGalaxyInterface
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
     * @return AnsibleGalaxyInterface
     */
    public function remove($roles = ''): AnsibleGalaxyInterface
    {
        $roles = $this->checkParam($roles, ' ');

        $this
            ->addBaseoption('remove')
            ->addBaseoption($roles);

        return $this;
    }

    /**
     * Show general or specific help.
     *
     * @return AnsibleGalaxyInterface
     */
    public function help(): AnsibleGalaxyInterface
    {
        $this->addParameter('--help');

        return $this;
    }

    /**
     * The path in which the skeleton role will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return AnsibleGalaxyInterface
     */
    public function initPath(string $path = ''): AnsibleGalaxyInterface
    {
        $this->addOption('--init-path', $path);

        return $this;
    }

    /**
     * Don't query the galaxy API when creating roles.
     *
     * @return AnsibleGalaxyInterface
     */
    public function offline(): AnsibleGalaxyInterface
    {
        $this->addParameter('--offline');

        return $this;
    }

    /**
     * The API server destination.
     *
     * @param string $apiServer
     * @return AnsibleGalaxyInterface
     */
    public function server(string $apiServer): AnsibleGalaxyInterface
    {
        $this->addOption('--server', $apiServer);

        return $this;
    }

    /**
     * Force overwriting an existing role.
     *
     * @return AnsibleGalaxyInterface
     */
    public function force(): AnsibleGalaxyInterface
    {
        $this->addParameter('--force');

        return $this;
    }

    /**
     * A file containing a list of roles to be imported.
     *
     * @param string $roleFile FILE
     * @return AnsibleGalaxyInterface
     */
    public function roleFile(string $roleFile): AnsibleGalaxyInterface
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
     * @return AnsibleGalaxyInterface
     */
    public function rolesPath(string $rolesPath): AnsibleGalaxyInterface
    {
        $this->addOption('--roles-path', $rolesPath);

        return $this;
    }

    /**
     * Ignore errors and continue with the next specified role.
     *
     * @return AnsibleGalaxyInterface
     */
    public function ignoreErrors(): AnsibleGalaxyInterface
    {
        $this->addParameter('--ignore-errors');

        return $this;
    }

    /**
     * Don't download roles listed as dependencies.
     *
     * @return AnsibleGalaxyInterface
     */
    public function noDeps(): AnsibleGalaxyInterface
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
    public function getCommandlineArguments(bool $asArray = true)
    {
        return $this->prepareArguments($asArray);
    }
}

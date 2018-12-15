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
 * Interface AnsibleGalaxyInterface
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface AnsibleGalaxyInterface extends AnsibleCommandInterface
{
    /**
     * Initialize a new role with base structure.
     *
     * @param string $roleName
     * @return AnsibleGalaxyInterface
     */
    public function init(string $roleName): AnsibleGalaxyInterface;

    /**
     * @param string $role
     * @param string $version
     * @return AnsibleGalaxyInterface
     */
    public function info(string $role, string $version = ''): AnsibleGalaxyInterface;

    /**
     * Install packages.
     *
     * If you are unsure whether the role(s) is already installed,
     * either check first or use the "force" option.
     *
     * @param string|array $roles role_name(s)[,version] | scm+role_repo_url[,version]
     * @return AnsibleGalaxyInterface
     */
    public function install($roles = ''): AnsibleGalaxyInterface;

    /**
     * Get a list of installed modules.
     *
     * @param string $roleName
     * @return AnsibleGalaxyInterface
     */
    public function modulelist(string $roleName = ''): AnsibleGalaxyInterface;

    /**
     * Add package(s)
     *
     * @param string|array $roles
     * @return AnsibleGalaxyInterface
     */
    public function remove($roles = ''): AnsibleGalaxyInterface;

    /**
     * Show general or specific help.
     *
     * @return AnsibleGalaxyInterface
     */
    public function help(): AnsibleGalaxyInterface;

    /**
     * The path in which the skeleton role will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return AnsibleGalaxyInterface
     */
    public function initPath(string $path = ''): AnsibleGalaxyInterface;

    /**
     * Don't query the galaxy API when creating roles.
     *
     * @return AnsibleGalaxyInterface
     */
    public function offline(): AnsibleGalaxyInterface;

    /**
     * The API server destination.
     *
     * @param string $apiServer
     * @return AnsibleGalaxyInterface
     */
    public function server(string $apiServer): AnsibleGalaxyInterface;

    /**
     * Force overwriting an existing role.
     *
     * @return AnsibleGalaxyInterface
     */
    public function force(): AnsibleGalaxyInterface;

    /**
     * A file containing a list of roles to be imported.
     *
     * @param string $roleFile FILE
     * @return AnsibleGalaxyInterface
     */
    public function roleFile(string $roleFile): AnsibleGalaxyInterface;

    /**
     * The path to the directory containing your roles.
     *
     * The default is the roles_path configured in your
     * ansible.cfg file (/etc/ansible/roles if not configured).
     *
     * @param string $rolesPath
     * @return AnsibleGalaxyInterface
     */
    public function rolesPath(string $rolesPath): AnsibleGalaxyInterface;

    /**
     * Ignore errors and continue with the next specified role.
     *
     * @return AnsibleGalaxyInterface
     */
    public function ignoreErrors(): AnsibleGalaxyInterface;

    /**
     * Don't download roles listed as dependencies.
     *
     * @return AnsibleGalaxyInterface
     */
    public function noDeps(): AnsibleGalaxyInterface;
}

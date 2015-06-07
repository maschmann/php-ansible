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
     * @return $this
     */
    public function init($roleName);

    /**
     * @param string $role
     * @param string $version
     * @return $this
     */
    public function info($role, $version = '');

    /**
     * Install packages.
     *
     * If you are unsure whether the role(s) is already installed,
     * either check first or use the "force" option.
     *
     * @param string|array $roles role_name(s)[,version] | scm+role_repo_url[,version]
     * @return $this
     */
    public function install($roles = '');

    /**
     * Get a list of installed modules.
     *
     * @param string $roleName
     * @return $this
     */
    public function modulelist($roleName = '');

    /**
     * Add package(s)
     *
     * @param string|array $roles
     * @return $this
     */
    public function remove($roles = '');

    /**
     * Show general or specific help.
     *
     * @return $this
     */
    public function help();

    /**
     * The path in which the skeleton role will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return $this
     */
    public function initPath($path = '');

    /**
     * Don't query the galaxy API when creating roles.
     *
     * @return $this
     */
    public function offline();

    /**
     * The API server destination.
     *
     * @param string $apiServer
     * @return $this
     */
    public function server($apiServer);

    /**
     * Force overwriting an existing role.
     *
     * @return $this
     */
    public function force();

    /**
     * A file containing a list of roles to be imported.
     *
     * @param string $roleFile FILE
     * @return $this
     */
    public function roleFile($roleFile);

    /**
     * The path to the directory containing your roles.
     *
     * The default is the roles_path configured in your
     * ansible.cfg file (/etc/ansible/roles if not configured).
     *
     * @param string $rolesPath
     * @return $this
     */
    public function rolesPath($rolesPath);

    /**
     * Ignore errors and continue with the next specified role.
     *
     * @return $this
     */
    public function ignoreErrors();

    /**
     * Don't download roles listed as dependencies.
     *
     * @return $this
     */
    public function noDeps();
}

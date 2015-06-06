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
     * @return string
     */
    public function info($role, $version = '');

    /**
     * Install packages.
     *
     * @param string|array $packages
     * @param boolean $upgrade upgrade package if already installed
     * @return $this
     */
    public function install($packages = '', $upgrade = true);

    /**
     * Get a list of installed modules.
     *
     * @return string list of installed modules
     */
    public function modulelist();

    /**
     * Add package(s)
     *
     * @param string|array $packages
     * @return $this
     */
    public function remove($packages = '');

    /**
     * Show general or specific help.
     *
     * @param string $command command to show help for
     * @return string
     */
    public function help($command = '');

    /**
     * The path in which the skeleton role will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return $this
     */
    public function initPath($path = '');

    /**
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
     * @return $this
     */
    public function force();

    /**
     * A file containing a list of roles to be imported.
     *
     * @param string $roleFile
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

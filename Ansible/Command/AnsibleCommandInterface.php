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
 * Interface AnsibleCommandInterface
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
interface AnsibleCommandInterface
{
    /**
     * Executes a command process
     *
     * @param null $callback optional callback to send to process->run()
     * @return stderr|stdout
     */
    public function execute($callback = null);
}

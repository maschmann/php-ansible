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

use Symfony\Component\Process\ProcessBuilder;

/**
 * Class AbstractAnsibleCommand
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
abstract class AbstractAnsibleCommand
{
    /**
     * @var ProcessBuilder
     */
    protected $process;

    /**
     * @param ProcessBuilder $process
     */
    public function __construct(ProcessBuilder $process)
    {
        $this->process = $process;
    }
}

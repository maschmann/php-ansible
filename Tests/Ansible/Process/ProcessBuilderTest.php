<?php
declare(strict_types=1);
/*
 * This file is part of the <package> package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Ansible\Test\Process;

use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Process\ProcessBuilderInterface;
use Asm\Test\AnsibleTestCase;
use Symfony\Component\Process\Process;

class ProcessBuilderTest extends AnsibleTestCase
{

    /**
     * @return ProcessBuilderInterface
     */
    public function testCreateInstance()
    {
        $processBuilder = new ProcessBuilder($this->getGalaxyUri(), $this->getProjectUri());
        $this->assertInstanceOf(ProcessBuilderInterface::class, $processBuilder);

        return $processBuilder;
    }

    /**
     * @param $processBuilder
     * @depends testCreateInstance
     */
    public function testGetProcess(ProcessBuilderInterface $processBuilder)
    {
        $process = $processBuilder
            ->setArguments([])
            ->setTimeout(5)
            ->getProcess();

        $this->assertInstanceOf(Process::class, $process);
    }
}


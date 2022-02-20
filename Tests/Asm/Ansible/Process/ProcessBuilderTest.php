<?php

declare(strict_types=1);

namespace Asm\Ansible\Process;

use Asm\Ansible\Testing\AnsibleTestCase;
use Symfony\Component\Process\Process;

class ProcessBuilderTest extends AnsibleTestCase
{

    /**
     * @return ProcessBuilderInterface
     */
    public function testCreateInstance(): ProcessBuilderInterface
    {
        $processBuilder = new ProcessBuilder($this->getGalaxyUri(), $this->getProjectUri());
        $this->assertInstanceOf(ProcessBuilderInterface::class, $processBuilder);

        return $processBuilder;
    }

    /**
     * @param ProcessBuilderInterface $processBuilder
     * @depends testCreateInstance
     */
    public function testGetProcess(ProcessBuilderInterface $processBuilder): void
    {
        $process = $processBuilder
            ->setArguments(['more_args'])
            ->setEnv('SOME', 'value')
            ->setTimeout(5)
            ->getProcess();

        $this->assertInstanceOf(Process::class, $process);

        // verify, all args are kept and merged correctly
        // Process component escapes all arguments with ''
        $this->assertEquals("'{$this->getGalaxyUri()}' 'more_args'", $process->getCommandLine());
    }
}


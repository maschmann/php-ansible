<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Tests\Ansible\Command;


use Asm\Ansible\Command\AnsiblePlaybook;
use Asm\Ansible\Command\AnsiblePlaybookInterface;
use Symfony\Component\Process\ProcessBuilder;

class AnsibleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return AnsiblePlaybookInterface
     */
    public function testCreateInstance()
    {
        $process = new ProcessBuilder();
        $process->setPrefix('ansible-playbook');

        $ansible = new AnsiblePlaybook($process);

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsiblePlaybook', $ansible);

        return $ansible;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     */
    public function testExecute(AnsiblePlaybookInterface $command)
    {
        $command->execute();
    }
}

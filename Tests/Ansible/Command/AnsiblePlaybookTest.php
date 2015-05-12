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
use SebastianBergmann\Comparator\DateTimeComparator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class AnsibleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return AnsiblePlaybookInterface
     */
    public function testCreateInstance()
    {
        $process = new ProcessBuilder();
        $process
            ->setPrefix('ansible-playbook')
            ->setWorkingDirectory('/home/wwwdev/htdocs/ansible-deploy');

        $ansible = new AnsiblePlaybook($process);

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsiblePlaybook', $ansible);

        return $ansible;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testDefaultDeployment(AnsiblePlaybookInterface $command)
    {
        $today = new \DateTime();

        $command
            ->play('kuechenkunst.yml')
            ->user('maschmann')
            ->extraVars(['project_release=' . $today->getTimestamp()])
            ->limit('test')
            ->check();

        return $command;
    }

    /**
     * @depends testDefaultDeployment
     * @param AnsiblePlaybookInterface $command
     */
    public function testExecute(AnsiblePlaybookInterface $command)
    {
        echo $command
            ->execute(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });
    }
}

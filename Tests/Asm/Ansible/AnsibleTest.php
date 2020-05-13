<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Ansible;

use Asm\Ansible\Exception\CommandException;
use Asm\Ansible\Testing\AnsibleTestCase;
use org\bovigo\vfs\vfsStream;

class AnsibleTest extends AnsibleTestCase
{
     /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testInstance()
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
        $this->assertInstanceOf('\Asm\Ansible\Ansible', $ansible, 'Instantiation with given paths');
    }

    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleProjectPathNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            'xxxxxxxx',
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleCommandNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            $this->getProjectUri(),
            '/tmp/ansible-playbook',
            '/tmp/ansible-galaxy'
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleNoCommandGivenException()
    {
        $this->markTestIncomplete();
        new Ansible(
            $this->getProjectUri()
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleCommandNotExecutableException()
    {
        $this->expectException(CommandException::class);
        $vfs = vfsStream::setup('/tmp');
        $ansiblePlaybook = vfsStream::newFile('ansible-playbook', 600)->at($vfs);
        $ansibleGalaxy = vfsStream::newFile('ansible-galaxy', 444)->at($vfs);

        new Ansible(
            $this->getProjectUri(),
            $ansiblePlaybook->url(),
            $ansibleGalaxy->url()
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::playbook
     * @covers \Asm\Ansible\Ansible::createProcess
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testPlaybookCommandInstance()
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );

        $playbook = $ansible->playbook();

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsiblePlaybook', $playbook);
    }

    /**
     * @covers \Asm\Ansible\Ansible::galaxy
     * @covers \Asm\Ansible\Ansible::createProcess
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testGalaxyCommandInstance()
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );

        $galaxy = $ansible->galaxy();

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsibleGalaxy', $galaxy);
    }
}

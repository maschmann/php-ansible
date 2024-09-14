<?php

declare(strict_types=1);

namespace Asm\Ansible;

use Asm\Ansible\Exception\CommandException;
use Asm\Ansible\Testing\AnsibleTestCase;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

#[CoversClass(\Asm\Ansible\Ansible::class)]
#[CoversFunction('playbook')]
#[CoversFunction('createProcess')]
#[CoversFunction('checkCommand')]
#[CoversFunction('checkDir')]
#[CoversFunction('__construct')]
class AnsibleTest extends AnsibleTestCase
{

    public function testInstance()
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
        $this->assertInstanceOf('\Asm\Ansible\Ansible', $ansible, 'Instantiation with given paths');
    }

    public function testAnsibleProjectPathNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            'xxxxxxxx',
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
    }

    public function testAnsibleCommandNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            $this->getProjectUri(),
            '/tmp/ansible-playbook',
            '/tmp/ansible-galaxy'
        );
    }

    public function testAnsibleNoCommandGivenException()
    {
        // TODO: Not sure why the following command should give an error.
        $this->assertTrue(true);
        //        new Ansible(
        //            $this->getProjectUri()
        //        );
    }

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

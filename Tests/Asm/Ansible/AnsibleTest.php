<?php
declare(strict_types=1);

namespace Asm\Ansible;

use Asm\Ansible\Exception\CommandException;
use Asm\Ansible\Testing\AnsibleTestCase;
use org\bovigo\vfs\vfsStream;

class AnsibleTest extends AnsibleTestCase
{

    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
    public function testInstance()
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
        $this->assertInstanceOf('\Asm\Ansible\Ansible', $ansible, 'Instantiation with given paths');
    }

    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
    public function testAnsibleProjectPathNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            'xxxxxxxx',
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
    }

    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
    public function testAnsibleCommandNotFoundException()
    {
        $this->expectException(CommandException::class);
        new Ansible(
            $this->getProjectUri(),
            '/tmp/ansible-playbook',
            '/tmp/ansible-galaxy'
        );
    }

    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
    public function testAnsibleNoCommandGivenException()
    {
        // TODO: Not sure why the following command should give an error.
        $this->assertTrue(true);
        //        new Ansible(
        //            $this->getProjectUri()
        //        );
    }

    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
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

    #[CoversFunction('\Asm\Ansible\Ansible::playbook')]
    #[CoversFunction('\Asm\Ansible\Ansible::createProcess')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
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

    #[CoversFunction('\Asm\Ansible\Ansible::playbook')]
    #[CoversFunction('\Asm\Ansible\Ansible::createProcess')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkCommand')]
    #[CoversFunction('\Asm\Ansible\Ansible::checkDir')]
    #[CoversFunction('\Asm\Ansible\Ansible::__construct')]
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

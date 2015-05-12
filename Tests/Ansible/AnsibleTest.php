<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Tests\Ansible;

use Asm\Ansible\Ansible;
use Asm\Ansible\Command\AnsibleGalaxy;
use Asm\Ansible\Command\AnsiblePlaybook;
use org\bovigo\vfs\vfsStream;

class AnsibleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testInstance()
    {
        $project = vfsStream::setup('/tmp/ansible-project');

        $ansible = new Ansible($project->url());
        $this->assertInstanceOf('\Asm\Ansible\Ansible', $ansible, 'Instantiation with ansible PATH check');

        $vfs = vfsStream::setup('/tmp');
        $ansiblePlaybook = vfsStream::newFile('ansible-playbook', 755)->at($vfs);
        $ansibleGalaxy = vfsStream::newFile('ansible-galaxy', 755)->at($vfs);

        $ansible = new Ansible(
            $project->url(),
            $ansiblePlaybook->url(),
            $ansibleGalaxy->url()
        );
        $this->assertInstanceOf('\Asm\Ansible\Ansible', $ansible, 'Instantiation with given paths');
    }

    /**
     * @expectedException \ErrorException
     */
    public function testAnsibleProjectPathNotFoundException()
    {
        $vfs = vfsStream::setup('/tmp');
        $ansiblePlaybook = vfsStream::newFile('ansible-playbook')->at($vfs);
        $ansibleGalaxy = vfsStream::newFile('ansible-galaxy')->at($vfs);

        $ansible = new Ansible(
            'xxxxxxxx',
            $ansiblePlaybook->url(),
            $ansibleGalaxy->url()
        );
    }

    /**
     * @expectedException \ErrorException
     */
    public function testAnsibleCommandNotFoundException()
    {
        $project = vfsStream::setup('/tmp/ansible-project');

        $ansible = new Ansible(
            $project->url(),
            '/tmp/ansible-playbook',
            '/tmp/ansible-galaxy'
        );
    }

    /**
     * @expectedException \ErrorException
     */
    public function testAnsibleCommandNotExecutableException()
    {
        $project = vfsStream::setup('/tmp/ansible-project');
        $vfs = vfsStream::setup('/tmp');
        $ansiblePlaybook = vfsStream::newFile('ansible-playbook')->at($vfs);
        $ansibleGalaxy = vfsStream::newFile('ansible-galaxy')->at($vfs);

        $ansible = new Ansible(
            $project->url(),
            $ansiblePlaybook->url(),
            $ansibleGalaxy->url()
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::playbook
     * @covers \Asm\Ansible\Ansible::createProcess
     */
    public function testPlaybookCommandInstance()
    {
        $project = vfsStream::setup('/tmp/ansible-project');
        $ansible = new Ansible($project->url());
        $playbook = $ansible->playbook();

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsiblePlaybook', $playbook);
    }

    /**
     * @covers \Asm\Ansible\Ansible::galaxy
     * @covers \Asm\Ansible\Ansible::createProcess
     */
    public function testGalaxyCommandInstance()
    {
        $project = vfsStream::setup('/tmp/ansible-project');
        $ansible = new Ansible($project->url());
        $galaxy = $ansible->galaxy();

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsibleGalaxy', $galaxy);
    }
}

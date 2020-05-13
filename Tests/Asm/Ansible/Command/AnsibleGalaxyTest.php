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

use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Testing\AnsibleTestCase;
use Asm\Ansible\Utils\Env;
use Psr\Log\NullLogger;

class AnsibleGalaxyTest extends AnsibleTestCase
{
    /**
     * @return AnsibleGalaxyInterface
     */
    public function testCreateInstance()
    {
        $process = new ProcessBuilder($this->getGalaxyUri(), $this->getProjectUri());
        $ansibleGalaxy = new AnsibleGalaxy($process);

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsibleGalaxy', $ansibleGalaxy);

        return $ansibleGalaxy;
    }

    /**
     * @depends testCreateInstance
     * @param AnsibleGalaxyInterface $command
     */
    public function testExecute(AnsibleGalaxyInterface $command)
    {
        // Skipped on Windows
        if (Env::isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $command->execute();

        // if command executes without exception
        $this->assertTrue(true);
    }

    public function testInit()
    {
        $command = $this->testCreateInstance();

        $command
            ->init('test_role')
            ->initPath('/tmp/php-ansible')
            ->force()
            ->offline()
            ->server('http://ansible.com');

        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('init test_role', $arguments);
        $this->assertArrayHasKey('--init-path=/tmp/php-ansible', $arguments);
        $this->assertArrayHasKey('--force', $arguments);
        $this->assertArrayHasKey('--offline', $arguments);
        $this->assertArrayHasKey('--server=http://ansible.com', $arguments);
    }

    public function testInfo()
    {
        $command = $this->testCreateInstance();
        $command->info('test_role');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('info test_role', $arguments);
    }

    public function testInfoWithVersion()
    {
        $command = $this->testCreateInstance();
        $command->info('test_role', '1.0');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('info test_role,1.0', $arguments);
    }

    public function testInstall()
    {
        $command = $this->testCreateInstance();
        $command->install('test_role');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('install test_role', $arguments);
    }

    public function testInstallWithRoles()
    {
        $command = $this->testCreateInstance();
        $command->install(
            [
                'test_role',
                'another_role',
                'yet_another_role'
            ]
        );
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('install test_role another_role yet_another_role', $arguments);
    }

    public function testInstallWithOptions()
    {
        $command = $this->testCreateInstance();

        $command
            ->install()
            ->roleFile('test_role')
            ->rolesPath('/tmp/roles')
            ->ignoreErrors()
            ->noDeps()
            ->server('http://ansible.com')
            ->force();


        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('install', $arguments);
        $this->assertArrayHasKey('--role-file=test_role', $arguments);
        $this->assertArrayHasKey('--roles-path=/tmp/roles', $arguments);
        $this->assertArrayHasKey('--server=http://ansible.com', $arguments);
        $this->assertArrayHasKey('--force', $arguments);
        $this->assertArrayHasKey('--ignore-errors', $arguments);
        $this->assertArrayHasKey('--no-deps', $arguments);
    }

    public function testList()
    {
        $command = $this->testCreateInstance();
        $command->modulelist();

        $this->assertEquals('list', $command->getCommandlineArguments(false));
    }

    public function testListWithRole()
    {
        $command = $this->testCreateInstance();
        $command->modulelist('test_role');

        $this->assertEquals('list test_role', $command->getCommandlineArguments(false));
    }

    public function testListWithHelp()
    {
        $command = $this->testCreateInstance();
        $command
            ->modulelist()
            ->help();

        $this->assertEquals('list --help', $command->getCommandlineArguments(false));
    }

    public function testRemove()
    {
        $command = $this->testCreateInstance();
        $command->remove('test_role');

        $this->assertEquals('remove test_role', $command->getCommandlineArguments(false));
    }

    public function testRemoveRoles()
    {
        $command = $this->testCreateInstance();
        $command->remove(
            [
                'test_role',
                'another_role'
            ]
        );

        $this->assertEquals('remove test_role another_role', $command->getCommandlineArguments(false));
    }
}

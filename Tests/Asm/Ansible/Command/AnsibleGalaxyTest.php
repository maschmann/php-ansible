<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Testing\AnsibleTestCase;
use Asm\Ansible\Utils\Env;

class AnsibleGalaxyTest extends AnsibleTestCase
{
    /**
     * @return AnsibleGalaxyInterface
     */
    public function testCreateInstance(): AnsibleGalaxyInterface
    {
        $process = new ProcessBuilder($this->getGalaxyUri(), $this->getProjectUri());
        $ansibleGalaxy = new AnsibleGalaxy($process);

        $this->assertInstanceOf(AnsibleGalaxy::class, $ansibleGalaxy);

        return $ansibleGalaxy;
    }

    public function testExecute(): void
    {
        // Skipped on Windows
        if (Env::isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $command = $this->testCreateInstance();
        $command->execute();

        // if command executes without exception
        $this->assertTrue(true);
    }

    public function testInit(): void
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

    public function testInfo(): void
    {
        $command = $this->testCreateInstance();
        $command->info('test_role');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('info test_role', $arguments);
    }

    public function testInfoWithVersion(): void
    {
        $command = $this->testCreateInstance();
        $command->info('test_role', '1.0');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('info test_role,1.0', $arguments);
    }

    public function testInstall(): void
    {
        $command = $this->testCreateInstance();
        $command->install('test_role');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('install test_role', $arguments);
    }

    public function testInstallWithRoles(): void
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

    public function testInstallWithOptions(): void
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

    public function testList(): void
    {
        $command = $this->testCreateInstance();
        $command->modulelist();

        $this->assertEquals('list', $command->getCommandlineArguments(false));
    }

    public function testListWithRole(): void
    {
        $command = $this->testCreateInstance();
        $command->modulelist('test_role');

        $this->assertEquals('list test_role', $command->getCommandlineArguments(false));
    }

    public function testListWithHelp(): void
    {
        $command = $this->testCreateInstance();
        $command
            ->modulelist()
            ->help();

        $this->assertEquals('list --help', $command->getCommandlineArguments(false));
    }

    public function testRemove(): void
    {
        $command = $this->testCreateInstance();
        $command->remove('test_role');

        $this->assertEquals('remove test_role', $command->getCommandlineArguments(false));
    }

    public function testRemoveRoles(): void
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

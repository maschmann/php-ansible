<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Testing\AnsibleTestCase;
use Asm\Ansible\Utils\Env;

class AnsibleGalaxyCollectionTest extends AnsibleTestCase
{
    /**
     * @return AnsibleGalaxyCollectionInterface
     */
    public function testCreateInstance(): AnsibleGalaxyCollectionInterface
    {
        $process = new ProcessBuilder($this->getGalaxyUri(), $this->getProjectUri());
        $ansibleGalaxyCollection = new AnsibleGalaxyCollection($process);

        $this->assertInstanceOf(AnsibleGalaxyCollection::class, $ansibleGalaxyCollection);

        return $ansibleGalaxyCollection;
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
            ->init('test_collection')
            ->initPath('/tmp/php-ansible')
            ->force();

        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('init', $arguments);
        $this->assertArrayHasKey('test_collection', $arguments);
        $this->assertArrayHasKey('--init-path=/tmp/php-ansible', $arguments);
        $this->assertArrayHasKey('--force', $arguments);
    }

    public function testBuild(): void
    {
        $command = $this->testCreateInstance();
        $command->build();
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('build', $arguments);
    }

    public function testBuildWithPath(): void
    {
        $command = $this->testCreateInstance();
        $command->build('/path/to/collection');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('build', $arguments);
        $this->assertArrayHasKey('/path/to/collection', $arguments);
    }

    public function testPublish(): void
    {
        $command = $this->testCreateInstance();
        $command->publish('namespace-collection-1.0.0.tar.gz');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('publish', $arguments);
        $this->assertArrayHasKey('namespace-collection-1.0.0.tar.gz', $arguments);
    }

    public function testInstall(): void
    {
        $command = $this->testCreateInstance();
        $command->install('test_collection');
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('install', $arguments);
        $this->assertArrayHasKey('test_collection', $arguments);
    }

    public function testInstallWithCollections(): void
    {
        $command = $this->testCreateInstance();
        $command->install(
            [
                'test_collection',
                'another_collection',
                'yet_another_collection'
            ]
        );
        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('install', $arguments);
        $this->assertArrayHasKey('test_collection', $arguments);
        $this->assertArrayHasKey('another_collection', $arguments);
        $this->assertArrayHasKey('yet_another_collection', $arguments);
    }

    public function testInstallWithOptions(): void
    {
        $command = $this->testCreateInstance();

        $command
            ->install()
            ->collectionsPath('/tmp/collections')
            ->force();

        $arguments = array_flip($command->getCommandlineArguments());

        $this->assertArrayHasKey('collection', $arguments);
        $this->assertArrayHasKey('install', $arguments);
        $this->assertArrayHasKey('--collections-path=/tmp/collections', $arguments);
        $this->assertArrayHasKey('--force', $arguments);
    }
}

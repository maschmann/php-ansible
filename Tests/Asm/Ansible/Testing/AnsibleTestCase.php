<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Ansible\Testing;

use Asm\Ansible\Utils\Env;
use LogicException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * Class AnsibleTestCase
 *
 * @package Asm\Test
 * @author  Marc Aschmann <maschmann@gmail.com>
 */
abstract class AnsibleTestCase extends TestCase
{
    protected vfsStreamFile $ansiblePlaybook;

    protected vfsStreamFile $ansibleGalaxy;

    protected vfsStreamDirectory $project;

    protected ?string $testRootPath = null;

    protected function setUp(): void
    {
        $this->createProjectStructure();
    }

    /**
     * Setup file system structure for tests.
     */
    protected function createProjectStructure(): vfsStreamDirectory
    {
        $projectStructure = [
            'ansible-project' => [
                'testproject.yml' => $this->getPlayContent(),
                'testproject' => $this->getInventoryContent(),
            ],
        ];

        $this->project = vfsStream::setup('root', null, $projectStructure);

        return $this->project;
    }

    protected function getProjectUri(): string
    {
        return $this->project->url() . '/ansible-project';
    }

    protected function getPlayUri(): string
    {
        return $this->project->url() . '/ansible-project/testproject.yml';
    }

    protected function getInventoryUri(): string
    {
        return $this->project->url() . '/ansible-project/testproject';
    }

    protected function getPlaybookUri(): string
    {
        Env::isWindows() ?
            $command = 'ansible-playbook.bat' :
            $command = 'ansible-playbook';

        return implode('/', [$this->getAssetsBinPath(), $command]);
    }

    protected function getGalaxyUri(): string
    {
        Env::isWindows() ?
            $command = 'ansible-galaxy.bat' :
            $command = 'ansible-galaxy';

        return implode('/', [$this->getAssetsBinPath(), $command]);
    }

    protected function getPlaybookContent(): string
    {
        return <<<EOT
#!/bin/bash
return 1
EOT;
    }

    protected function getGalaxyContent(): string
    {
        return <<<EOT
#!/bin/bash
return 1
EOT;
    }

    protected function getPlayContent(): string
    {
        return <<<EOT
- hosts: test
  vars: []
  roles: []
EOT;
    }

    protected function getInventoryContent(): string
    {
        return <<<EOT
[test]
127.0.0.1
EOT;
    }

    /**
     * Returns the absolute path of the Tests/ folder
     * @return string
     */
    protected function getTestRootPath(): string
    {
        if ($this->testRootPath === null) {
            $this->testRootPath = realpath(__DIR__ . str_repeat('/..', 3));
            if ($this->testRootPath === false) {
                throw new LogicException('Cannot identify the Tests/ root path.');
            }
            $this->testRootPath = str_replace('\\', '/', $this->testRootPath);
        }

        return $this->testRootPath;
    }

    /**
     * Returns the assets path.
     * @return string
     */
    protected function getAssetsPath(): string
    {
        return implode('/', [$this->getTestRootPath(), 'assets']);
    }

    /**
     * Returns the assets path.
     * @return string
     */
    protected function getAssetsBinPath(): string
    {
        return implode('/', [$this->getAssetsPath(), 'bin']);
    }

    /**
     * Returns the assets path.
     * @return string
     */
    protected function getSamplesPath(): string
    {
        return implode('/', [$this->getAssetsPath(), 'samples']);
    }

    /**
     * Returns the assets path for a given class.
     *
     * Example:
     * ```php
     * $path = $this->getSamplesPathFor(Ansible::class);
     *
     * // Returns
     * // /your/project/pathTests/samples/Asm/Ansible
     * ```
     *
     * @param string $class The class FQN. E.g. Asm\Ansibe\Ansible
     * @return string
     */
    protected function getSamplesPathFor(string $class): string
    {
        return implode('/', [$this->getSamplesPath(), str_replace('\\', '/', $class)]);
    }
}

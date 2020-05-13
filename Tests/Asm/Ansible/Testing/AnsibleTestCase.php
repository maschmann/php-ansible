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
    /**
     * @var vfsStreamFile
     */
    protected $ansiblePlaybook;
    /**
     * @var vfsStreamFile
     */
    protected $ansibleGalaxy;
    /**
     * @var
     */
    protected $project;
    /**
     * @var string|null
     */
    protected $testRootPath = null;

    /**
     * default setup
     */
    protected function setUp()
    {
        $this->createProjectStructure();
    }

    /**
     * Setup file system structure for tests.
     */
    protected function createProjectStructure()
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

    /**
     * @return string
     */
    protected function getProjectUri()
    {
        return $this->project->url() . '/ansible-project';
    }

    /**
     * @return string
     */
    protected function getPlayUri()
    {
        return $this->project->url() . '/ansible-project/testproject.yml';
    }

    /**
     * @return string
     */
    protected function getInventoryUri()
    {
        return $this->project->url() . '/ansible-project/testproject';
    }

    /**
     * @return string
     */
    protected function getPlaybookUri()
    {
        Env::isWindows() ?
            $command = 'ansible-playbook.bat' :
            $command = 'ansible-playbook';

        return implode('/', [$this->getAssetsBinPath(), $command]);
    }

    /**
     * @return string
     */
    protected function getGalaxyUri()
    {
        Env::isWindows() ?
            $command = 'ansible-galaxy.bat' :
            $command = 'ansible-galaxy';

        return implode('/', [$this->getAssetsBinPath(), $command]);
    }

    /**
     * @return string
     */
    protected function getPlaybookContent()
    {
        return <<<EOT
#!/bin/bash
return 1
EOT;
    }

    /**
     * @return string
     */
    protected function getGalaxyContent()
    {
        return <<<EOT
#!/bin/bash
return 1
EOT;
    }

    /**
     * @return string
     */
    protected function getPlayContent()
    {
        return <<<EOT
- hosts: test
  vars: []
  roles: []
EOT;
    }

    /**
     * @return string
     */
    protected function getInventoryContent()
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

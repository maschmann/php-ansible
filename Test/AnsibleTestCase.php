<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Test;


use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

/**
 * Class AnsibleTestCase
 *
 * @package Asm\Test
 * @author Marc Aschmann <maschmann@gmail.com>
 */
abstract class AnsibleTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamFile
     */
    protected $ansiblePlaybook;

    /**
     * @var \org\bovigo\vfs\vfsStreamFile
     */
    protected $ansibleGalaxy;

    /**
     * @var
     */
    protected $project;

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

        /*$this->ansiblePlaybook = vfsStream::newFile('ansible-playbook', 755)
            ->at($this->project)
            ->setContent($this->getPlaybookContent());
        $this->ansibleGalaxy = vfsStream::newFile('ansible-galaxy', 755)
            ->at($this->project)
            ->setContent($this->getGalaxyContent());
        */

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
        //return $this->ansiblePlaybook->url();
        return './Test/ansible-playbook';
    }

    /**
     * @return string
     */
    protected function getGalaxyUri()
    {
        //return $this->ansibleGalaxy->url();
        return './Test/ansible-galaxy';
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
}

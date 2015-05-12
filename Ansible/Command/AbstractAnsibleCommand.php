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

use Symfony\Component\Process\ProcessBuilder;

/**
 * Class AbstractAnsibleCommand
 *
 * @package Asm\Ansible\Command
 * @author Marc Aschmann <maschmann@gmail.com>
 */
abstract class AbstractAnsibleCommand
{
    /**
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
        $this->options = [];
        $this->parameters = [];
    }

    /**
     * Add an Option.
     *
     * @param string $name
     * @param string $value
     */
    protected function addOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Add a parameter.
     *
     * @param $name
     */
    protected function addParameter($name)
    {
        $this->parameters[] = $name;
    }

    /**
     * Get all options as array.
     *
     * @return string
     */
    protected function getOptions()
    {
        $options = [];

        foreach ($this->options as $name => $value) {
            $options[] = $name . '=' . $value;
        }

        return $options;
    }

    /**
     * Get all parameters as array.
     *
     * @return string
     */
    protected function getParameters()
    {
        return $this->parameters;
    }
}

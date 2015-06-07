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
     * @var array
     */
    private $baseOptions;

    /**
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
        $this->options = [];
        $this->parameters = [];
        $this->baseOptions = [];
    }

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string[]
     */
    protected function prepareArguments($asArray = true)
    {
        $arguments = array_merge(
            [$this->getBaseOptions()],
            $this->getOptions(),
            $this->getParameters()
        );

        if (false === $asArray) {
            $arguments = implode(' ', $arguments);
        }

        return $arguments;
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
     * @param string $name
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


    /**
     * Add base options to internal storage.
     *
     * @param string $baseOption
     * @return $this
     */
    protected function addBaseoption($baseOption)
    {
        $this->baseOptions[] = $baseOption;

        return $this;
    }

    /**
     * Generate base options string.
     *
     * @return string
     */
    protected function getBaseOptions()
    {
        return implode(' ', $this->baseOptions);
    }

    /**
     * Check if param is array or string and implode with glue if necessary.
     *
     * @param string|array $param
     * @param string $glue
     * @return string
     */
    protected function checkParam($param, $glue = ',')
    {
        if (true == is_array($param)) {
            $param = implode($glue, $param);
        }

        return $param;
    }
}

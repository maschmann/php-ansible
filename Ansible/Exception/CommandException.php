<?php
namespace Asm\Ansible\Exception;

/**
 * Class CommandException
 *
 * @package Asm\Ansible\Exception
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class CommandException extends \RuntimeException
{
    protected $code = 500;
}


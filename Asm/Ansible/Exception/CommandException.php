<?php
namespace Asm\Ansible\Exception;

use RuntimeException;

/**
 * Class CommandException
 *
 * @package Asm\Ansible\Exception
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class CommandException extends RuntimeException
{
    protected $code = 500;
}


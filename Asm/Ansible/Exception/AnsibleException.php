<?php

declare(strict_types=1);

namespace Asm\Ansible\Exception;

class AnsibleException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        private readonly ?string $command = null,
        private readonly ?string $output = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }
}

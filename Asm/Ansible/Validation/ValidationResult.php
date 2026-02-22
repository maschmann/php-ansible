<?php

declare(strict_types=1);

namespace Asm\Ansible\Validation;

readonly class ValidationResult
{
    public function __construct(
        private bool $isValid,
        private array $errors = [],
        private array $warnings = []
    ) {
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }
}

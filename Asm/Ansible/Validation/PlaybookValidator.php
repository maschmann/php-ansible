<?php

declare(strict_types=1);

namespace Asm\Ansible\Validation;

use Asm\Ansible\Process\ProcessRunner;

class PlaybookValidator
{
    public function __construct(
        private readonly ProcessRunner $processRunner
    ) {
    }

    public function validate(string $playbookPath): ValidationResult
    {
        $result = $this->processRunner->run([
            'ansible-playbook', '--syntax-check', $playbookPath
        ]);

        $isValid = $result->isSuccessful();
        $errors = [];
        $warnings = [];

        if (!$isValid) {
            $errors = $this->parseErrors($result->getOutput());
        }

        return new ValidationResult($isValid, $errors, $warnings);
    }

    private function parseErrors(array $output): array
    {
        $errors = [];
        foreach ($output as $line) {
            if (str_contains($line, 'ERROR!')) {
                $errors[] = trim(str_replace('ERROR!', '', $line));
            }
        }
        return $errors;
    }
}

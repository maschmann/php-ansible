<?php

declare(strict_types=1);

namespace Asm\Ansible\Vault;

use Asm\Ansible\Process\ProcessRunner;
use Asm\Ansible\Process\ProcessResult;
use Asm\Ansible\Exception\VaultException;

class AnsibleVault
{
    public function __construct(
        private readonly ProcessRunner $processRunner
    ) {
    }

    public function encrypt(string $file, string $vaultPasswordFile): ProcessResult
    {
        $command = [
            'ansible-vault', 'encrypt', $file,
            '--vault-password-file', $vaultPasswordFile
        ];

        $result = $this->processRunner->run($command);

        if (!$result->isSuccessful()) {
            throw new VaultException(
                "Failed to encrypt file: $file",
                $result->getExitCode(),
                null,
                implode(' ', $command),
                $result->getOutput()
            );
        }

        return $result;
    }

    public function decrypt(string $file, string $vaultPasswordFile): ProcessResult
    {
        $command = [
            'ansible-vault', 'decrypt', $file,
            '--vault-password-file', $vaultPasswordFile
        ];

        $result = $this->processRunner->run($command);

        if (!$result->isSuccessful()) {
            throw new VaultException(
                "Failed to decrypt file: $file",
                $result->getExitCode(),
                null,
                implode(' ', $command),
                $result->getOutput()
            );
        }

        return $result;
    }

    public function view(string $file, string $vaultPasswordFile): ProcessResult
    {
        return $this->processRunner->run([
            'ansible-vault', 'view', $file,
            '--vault-password-file', $vaultPasswordFile
        ]);
    }
}

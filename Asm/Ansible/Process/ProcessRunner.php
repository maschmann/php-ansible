<?php

declare(strict_types=1);

namespace Asm\Ansible\Process;

use Asm\Ansible\Exception\AnsibleException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ProcessRunner
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ProcessBuilderInterface $processBuilder,
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    public function run(array $command, ?string $workingDirectory = null, ?int $timeout = null): ProcessResult
    {
        $this->logger->info('Executing command: ' . implode(' ', $command));

        $process = $this->processBuilder->setArguments($command);

        if ($workingDirectory !== null) {
            $process->setWorkingDirectory($workingDirectory);
        }

        if ($timeout !== null) {
            $process->setTimeout($timeout);
        }

        $result = $process->getProcess()->run();

        $processResult = new ProcessResult(
            $result,
            $process->getProcess()->getOutput(),
            $process->getProcess()->getErrorOutput()
        );

        if (!$processResult->isSuccessful()) {
            $this->logger->error('Command failed', [
                'command' => implode(' ', $command),
                'exit_code' => $processResult->getExitCode(),
                'output' => $processResult->getOutput(),
                'error' => $processResult->getErrorOutput()
            ]);
        }

        return $processResult;
    }
}

<?php

declare(strict_types=1);

namespace Asm\Ansible\Collection;

use Asm\Ansible\Process\ProcessRunner;
use Asm\Ansible\Process\ProcessResult;
use Asm\Ansible\Exception\CollectionException;

class AnsibleCollection
{
    public function __construct(
        private readonly ProcessRunner $processRunner
    ) {
    }

    public function install(string $collection, ?string $version = null): ProcessResult
    {
        $command = ['ansible-galaxy', 'collection', 'install'];
        $command[] = $version ? "$collection:$version" : $collection;

        $result = $this->processRunner->run($command);

        if (!$result->isSuccessful()) {
            throw new CollectionException(
                "Failed to install collection: $collection",
                $result->getExitCode(),
                null,
                implode(' ', $command),
                $result->getOutput()
            );
        }

        return $result;
    }

    public function list(): ProcessResult
    {
        return $this->processRunner->run(['ansible-galaxy', 'collection', 'list']);
    }

    public function uninstall(string $collection): ProcessResult
    {
        $command = ['ansible-galaxy', 'collection', 'remove', $collection];

        $result = $this->processRunner->run($command);

        if (!$result->isSuccessful()) {
            throw new CollectionException(
                "Failed to uninstall collection: $collection",
                $result->getExitCode(),
                null,
                implode(' ', $command),
                $result->getOutput()
            );
        }

        return $result;
    }
}

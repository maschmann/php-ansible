<?php

declare(strict_types=1);

namespace Asm\Ansible\Inventory;

use Asm\Ansible\Exception\InventoryException;
use Symfony\Component\Yaml\Yaml;

class InventoryManager
{
    private array $hosts = [];
    private array $groups = [];
    private array $variables = [];

    public function addHost(string $host, array $variables = []): self
    {
        $this->hosts[$host] = $variables;
        return $this;
    }

    public function addGroup(string $group, array $hosts = []): self
    {
        $this->groups[$group] = $hosts;
        return $this;
    }

    public function setGroupVariables(string $group, array $variables): self
    {
        $this->variables[$group] = $variables;
        return $this;
    }

    public function toYaml(): string
    {
        $inventory = ['all' => ['hosts' => $this->hosts]];

        foreach ($this->groups as $group => $hosts) {
            $inventory['all']['children'][$group] = ['hosts' => []];
            foreach ($hosts as $host) {
                $inventory['all']['children'][$group]['hosts'][$host] = null;
            }
        }

        foreach ($this->variables as $group => $vars) {
            if (isset($inventory['all']['children'][$group])) {
                $inventory['all']['children'][$group]['vars'] = $vars;
            }
        }

        return Yaml::dump($inventory);
    }

    public function saveToFile(string $filename): void
    {
        $yaml = $this->toYaml();
        if (file_put_contents($filename, $yaml) === false) {
            throw new InventoryException("Failed to save inventory to file: $filename");
        }
    }
}

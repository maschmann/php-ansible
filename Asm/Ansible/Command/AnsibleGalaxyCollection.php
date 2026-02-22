<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

/**
 * Class AnsibleGalaxyCollection
 *
 * @package Asm\Ansible\Command
 */
final class AnsibleGalaxyCollection extends AbstractAnsibleCommand implements AnsibleGalaxyCollectionInterface
{
    /**
     * Executes a command process.
     * Returns either exitcode or string output if no callback is given.
     *
     * @param callable|null $callback
     * @return integer|string
     */
    public function execute(?callable $callback = null): int|string
    {
        return $this->runProcess($callback);
    }

    /**
     * Initialize a new collection with base structure.
     *
     * @param string $collectionName
     * @return AnsibleGalaxyCollectionInterface
     */
    public function init(string $collectionName): AnsibleGalaxyCollectionInterface
    {
        $this
            ->addBaseOption('collection')
            ->addBaseOption('init')
            ->addBaseOption($collectionName);

        return $this;
    }

    /**
     * Build an Ansible collection artifact that can be published to Ansible Galaxy.
     *
     * @param string $collectionPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function build(string $collectionPath = ''): AnsibleGalaxyCollectionInterface
    {
        $this->addBaseOption('collection');
        $this->addBaseOption('build');

        if ('' !== $collectionPath) {
            $this->addBaseOption($collectionPath);
        }

        return $this;
    }

    /**
     * Publish a collection artifact to Ansible Galaxy.
     *
     * @param string $artifactPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function publish(string $artifactPath): AnsibleGalaxyCollectionInterface
    {
        $this->addBaseOption('collection');
        $this->addBaseOption('publish');
        $this->addBaseOption($artifactPath);

        return $this;
    }

    /**
     * Install collection(s).
     *
     * @param string|array $collections collection_name(s) | path/to/collection.tar.gz
     * @return AnsibleGalaxyCollectionInterface
     */
    public function install(string|array $collections = ''): AnsibleGalaxyCollectionInterface
    {
        $collections = $this->checkParam($collections, ' ');

        $this->addBaseOption('collection');
        $this->addBaseOption('install');

        if ('' !== $collections) {
            $collectionsArray = explode(' ', $collections);
            foreach ($collectionsArray as $collection) {
                if ($collection !== '') {
                    $this->addBaseOption($collection);
                }
            }
        }

        return $this;
    }

    /**
     * The path in which the skeleton collection will be created.
     * The default is the current working directory.
     *
     * @param string $path
     * @return AnsibleGalaxyCollectionInterface
     */
    public function initPath(string $path = ''): AnsibleGalaxyCollectionInterface
    {
        $this->addOption('--init-path', $path);

        return $this;
    }

    /**
     * Force overwriting an existing collection.
     *
     * @return AnsibleGalaxyCollectionInterface
     */
    public function force(): AnsibleGalaxyCollectionInterface
    {
        $this->addParameter('--force');

        return $this;
    }

    /**
     * The path to the directory containing your collections.
     *
     * @param string $collectionsPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function collectionsPath(string $collectionsPath): AnsibleGalaxyCollectionInterface
    {
        $this->addOption('--collections-path', $collectionsPath);

        return $this;
    }

    /**
     * Get parameter string which will be used to call ansible.
     *
     * @param bool $asArray
     * @return string|array
     */
    public function getCommandlineArguments(bool $asArray = true): string|array
    {
        return $this->prepareArguments($asArray);
    }
}

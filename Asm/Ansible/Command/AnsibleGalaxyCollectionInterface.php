<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

/**
 * Interface AnsibleGalaxyCollectionInterface
 *
 * @package Asm\Ansible\Command
 */
interface AnsibleGalaxyCollectionInterface extends AnsibleCommandInterface
{
    /**
     * Initialize a new collection with base structure.
     *
     * @param string $collectionName
     * @return AnsibleGalaxyCollectionInterface
     */
    public function init(string $collectionName): AnsibleGalaxyCollectionInterface;

    /**
     * Build an Ansible collection artifact that can be published to Ansible Galaxy.
     *
     * @param string $collectionPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function build(string $collectionPath = ''): AnsibleGalaxyCollectionInterface;

    /**
     * Publish a collection artifact to Ansible Galaxy.
     *
     * @param string $artifactPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function publish(string $artifactPath): AnsibleGalaxyCollectionInterface;

    /**
     * Install collection(s).
     *
     * @param string|array $collections collection_name(s) | path/to/collection.tar.gz
     * @return AnsibleGalaxyCollectionInterface
     */
    public function install(string|array $collections = ''): AnsibleGalaxyCollectionInterface;

    /**
     * The path in which the skeleton collection will be created.
     *
     * @param string $path
     * @return AnsibleGalaxyCollectionInterface
     */
    public function initPath(string $path = ''): AnsibleGalaxyCollectionInterface;

    /**
     * Force overwriting an existing collection.
     *
     * @return AnsibleGalaxyCollectionInterface
     */
    public function force(): AnsibleGalaxyCollectionInterface;

    /**
     * The path to the directory containing your collections.
     *
     * @param string $collectionsPath
     * @return AnsibleGalaxyCollectionInterface
     */
    public function collectionsPath(string $collectionsPath): AnsibleGalaxyCollectionInterface;
}

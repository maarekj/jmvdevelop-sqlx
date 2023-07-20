<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Cli;

use Doctrine\DBAL\Connection;
use JmvDevelop\Sqlx\Describer;
use JmvDevelop\Sqlx\QueryFinder\QueryFinder;
use JmvDevelop\Sqlx\TypeMapper;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixing\PathPrefixedAdapter;

final class GenerateCommandConfig
{
    public function __construct(
        private readonly TypeMapper $typeMapper,
        private readonly Connection $connection,
        private readonly Describer $describer,
        private readonly FilesystemAdapter $fsAdapter,
        private readonly string $namespace,
        private readonly QueryFinder $finder
    ) {
    }

    public function getTypeMapper(): TypeMapper
    {
        return $this->typeMapper;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getDescriber(): Describer
    {
        return $this->describer;
    }

    public function createFs(string $prefix = null): Filesystem
    {
        if (null === $prefix) {
            return new Filesystem($this->getFsAdapter());
        }

        return new Filesystem(new PathPrefixedAdapter($this->getFsAdapter(), $prefix));
    }

    public function getFsAdapter(): FilesystemAdapter
    {
        return $this->fsAdapter;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getFinder(): QueryFinder
    {
        return $this->finder;
    }
}

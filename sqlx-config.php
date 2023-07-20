<?php

declare(strict_types=1);

use JmvDevelop\Sqlx\Cli\GenerateCommandConfig;
use JmvDevelop\Sqlx\MysqlDescriber;
use JmvDevelop\Sqlx\QueryFinder\SymfonyQueryFinder;
use JmvDevelop\Sqlx\Tests\DatabaseManager;
use JmvDevelop\Sqlx\TypeMapper;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\Finder\Finder;

$dm = new DatabaseManager();
$dm->installSchema();

return new GenerateCommandConfig(
    connection: $dm->getMysqlDbal(),
    typeMapper: ($typeMapper = new TypeMapper()),
    describer: new MysqlDescriber(typeMapper: $typeMapper, connectionInfo: DatabaseManager::mysqliDatabaseConfig()),
    namespace: "JmvDevelop\\Sqlx\\Examples\\Generated",
    fsAdapter: new LocalFilesystemAdapter(__DIR__ . '/examples/Generated'),
    finder: new SymfonyQueryFinder(finder: (new Finder())
        ->in(__DIR__ . "/examples")
        ->files()
        ->name("*.sql")
    ),
);
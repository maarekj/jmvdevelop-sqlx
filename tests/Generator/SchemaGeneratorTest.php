<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Generator;

use JmvDevelop\Sqlx\Generator\SchemaGenerator;
use JmvDevelop\Sqlx\Tests\WithDatabaseManagerTrait;
use JmvDevelop\Sqlx\Tests\WithSchemaTrait;
use JmvDevelop\Sqlx\TypeMapper;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use function Psl\Dict\sort_by_key;

/** @internal */
#[CoversClass(SchemaGenerator::class)]
#[UsesClass(TypeMapper::class)]
#[UsesFunction('JmvDevelop\\Sqlx\\Utils\\assertNotNull')]
#[UsesFunction('JmvDevelop\\Sqlx\\Utils\\addCreateMethodInClassFromConstructor')]
final class SchemaGeneratorTest extends TestCase
{
    use WithDatabaseManagerTrait;
    use WithSchemaTrait;
    use MatchesSnapshots;

    public function testGenerate(): void
    {
        $fs = new Filesystem(new InMemoryFilesystemAdapter());
        $generator = new SchemaGenerator(
            connection: self::getDbManager()->getMysqlDbal(),
            fs: $fs,
            namespace: 'JmvDevelop\\Sqlx\\Tests\\Schema',
            typeMapper: new TypeMapper(),
        );

        $generator->generate();

        $results = [];
        foreach ($fs->listContents('/', FilesystemOperator::LIST_DEEP) as $content) {
            if ($content->isFile()) {
                $path = $content->path();
                $results[$path] = $fs->read($path);
            }
        }
        $this->assertMatchesSnapshot(sort_by_key($results));
    }
}

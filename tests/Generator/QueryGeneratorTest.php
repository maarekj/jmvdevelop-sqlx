<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Generator;

use JmvDevelop\Sqlx\ColumnDescription;
use JmvDevelop\Sqlx\Generator\QueryGenerator;
use JmvDevelop\Sqlx\MysqlDescriber;
use JmvDevelop\Sqlx\ParameterParser;
use JmvDevelop\Sqlx\QueryDescription;
use JmvDevelop\Sqlx\QueryFinder\Source;
use JmvDevelop\Sqlx\Tests\DatabaseManager;
use JmvDevelop\Sqlx\Tests\WithDatabaseManagerTrait;
use JmvDevelop\Sqlx\Tests\WithSchemaTrait;
use JmvDevelop\Sqlx\TypeMapper;
use JmvDevelop\Sqlx\Utils\ConvertParameters;
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
#[CoversClass(QueryGenerator::class)]
#[UsesClass(MysqlDescriber::class)]
#[UsesClass(QueryDescription::class)]
#[UsesClass(Source::class)]
#[UsesClass(ConvertParameters::class)]
#[UsesClass(ColumnDescription::class)]
#[UsesClass(ParameterParser::class)]
#[UsesClass(TypeMapper::class)]
#[UsesFunction('JmvDevelop\\Sqlx\\Utils\\assertNotNull')]
#[UsesFunction('JmvDevelop\\Sqlx\\Utils\\addCreateMethodInClassFromConstructor')]
#[UsesFunction('JmvDevelop\\Sqlx\\Utils\\stripCommentsInSql')]
final class QueryGeneratorTest extends TestCase
{
    use WithDatabaseManagerTrait;
    use WithSchemaTrait;
    use MatchesSnapshots;

    public function testGenerate(): void
    {
        $describer = new MysqlDescriber(
            connectionInfo: DatabaseManager::mysqliDatabaseConfig(),
            typeMapper: new TypeMapper(),
        );
        $fs = new Filesystem(new InMemoryFilesystemAdapter());
        $generator = new QueryGenerator(
            describer: $describer,
            fs: $fs,
            typeMapper: new TypeMapper(),
            namespace: 'JmvDevelop\\Sqlx\\Tests\\Generated'
        );

        $generator->generate(new Source(
            name: 'get_all_posts',
            realPath: 'inline',
            content: 'SELECT * FROM post',
        ));

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

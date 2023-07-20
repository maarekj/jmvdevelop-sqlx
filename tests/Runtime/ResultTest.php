<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Runtime;

use JmvDevelop\Sqlx\Examples\Generated\Query\FindCategoryByIdParams;
use JmvDevelop\Sqlx\Examples\Generated\Query\FindCategoryByIdQuery;
use JmvDevelop\Sqlx\Examples\Generated\Query\FindCategoryByIdRow;
use JmvDevelop\Sqlx\Examples\Generated\Query\GetAllCategoryQuery;
use JmvDevelop\Sqlx\Examples\Generated\Query\GetAllCategoryRow;
use JmvDevelop\Sqlx\Examples\Generated\Schema\CategoryObject;
use JmvDevelop\Sqlx\Exception\SqlxException;
use JmvDevelop\Sqlx\Runtime\DefaultValue;
use JmvDevelop\Sqlx\Runtime\Result;
use JmvDevelop\Sqlx\Tests\AssertExceptionTrait;
use JmvDevelop\Sqlx\Tests\WithDatabaseManagerTrait;
use JmvDevelop\Sqlx\Tests\WithSchemaTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(Result::class)]
#[UsesClass(DefaultValue::class)]
final class ResultTest extends TestCase
{
    use WithDatabaseManagerTrait;
    use WithSchemaTrait;
    use AssertExceptionTrait;

    public function test(): void
    {
        $dbal = $this->getDbManager()->getMysqlDbal();
        CategoryObject::create(name: 'Category 1')->insert(connection: $dbal);
        CategoryObject::create(name: 'Category 2')->insert(connection: $dbal);
        CategoryObject::create(name: 'Category 3')->insert(connection: $dbal);
        CategoryObject::create(name: 'Category 4')->insert(connection: $dbal);
        CategoryObject::create(name: 'Category 5')->insert(connection: $dbal);

        $expectedRows = [
            GetAllCategoryRow::create(id: 1, name: 'Category 1'),
            GetAllCategoryRow::create(id: 2, name: 'Category 2'),
            GetAllCategoryRow::create(id: 3, name: 'Category 3'),
            GetAllCategoryRow::create(id: 4, name: 'Category 4'),
            GetAllCategoryRow::create(id: 5, name: 'Category 5'),
        ];

        $result = GetAllCategoryQuery::create(connection: $dbal)->execute();
        $this->assertSame(5, $result->rowCount());
        $this->assertSame(2, $result->columnCount());

        $result = GetAllCategoryQuery::create(connection: $dbal)->execute();
        $this->assertEquals($expectedRows, $result->toArray());

        $result = GetAllCategoryQuery::create(connection: $dbal)->execute();
        $this->assertEquals($expectedRows, $result->toDoctrineCollection()->toArray());

        $result = GetAllCategoryQuery::create(connection: $dbal)->execute();
        $this->assertEquals($expectedRows, $result->toPslVector()->toArray());

        $result = GetAllCategoryQuery::create(connection: $dbal)->execute();
        $this->assertEquals($expectedRows, iterator_to_array($result->iterate()));

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame('Result already fetched. The result can be fetched only once.', $t->getMessage());
        }, function () use ($result): void {
            $result->toArray();
        });

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame('Result has more than one row.', $t->getMessage());
        }, function () use ($result): void {
            $result->firstOrNull();
        });

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame('Result has more than one row.', $t->getMessage());
        }, function () use ($result): void {
            $result->firstOrThrow();
        });

        $this->assertNull(FindCategoryByIdQuery::create(connection: $dbal, params: FindCategoryByIdParams::create(id: 100000))
            ->execute()
            ->firstOrNull());

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame('Result has no row.', $t->getMessage());
        }, function () use ($dbal): void {
            FindCategoryByIdQuery::create(connection: $dbal, params: FindCategoryByIdParams::create(id: 100000))
                ->execute()
                ->firstOrThrow()
            ;
        });

        $this->assertEquals(
            FindCategoryByIdRow::create(id: 1, name: 'Category 1'),
            FindCategoryByIdQuery::create(connection: $dbal, params: FindCategoryByIdParams::create(id: 1))
                ->execute()
                ->firstOrNull()
        );

        $this->assertEquals(
            FindCategoryByIdRow::create(id: 1, name: 'Category 1'),
            FindCategoryByIdQuery::create(connection: $dbal, params: FindCategoryByIdParams::create(id: 1))
                ->execute()
                ->firstOrThrow()
        );
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

use JmvDevelop\Sqlx\ColumnDescription;
use JmvDevelop\Sqlx\Exception\PrepareException;
use JmvDevelop\Sqlx\MysqlDescriber;
use JmvDevelop\Sqlx\QueryDescription;
use JmvDevelop\Sqlx\TypeMapper;
use JmvDevelop\Sqlx\Utils\ConvertParameters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(MysqlDescriber::class)]
#[CoversClass(PrepareException::class)]
#[UsesClass(QueryDescription::class)]
#[UsesClass(ConvertParameters::class)]
#[UsesClass(TypeMapper::class)]
#[UsesClass(ColumnDescription::class)]
final class MysqlDescriberTest extends TestCase
{
    use WithDatabaseManagerTrait;
    use WithSchemaTrait;
    use AssertExceptionTrait;

    public function testDescribeQuery(): void
    {
        $describer = new MysqlDescriber(
            typeMapper: new TypeMapper(),
            connectionInfo: DatabaseManager::mysqliDatabaseConfig()
        );

        $sql = <<<'SQL'
            SELECT p.id as p_id, 
                   p.title as p_title, 
                   p.h1 as p_h1, 
                   p.content as p_content, 
                   p.date_creation as p_date_creation, 
                   u.id as u_id, 
                   u.email as u_email
            FROM post p
            LEFT JOIN user u ON u.id = p.author_id
            WHERE u.id = :id
            SQL;
        $description = $describer->describeQuery($sql);

        $this->assertSame($sql, $description->getSql());
        $this->assertEquals([
            new ColumnDescription(
                name: 'p_id',
                dbType: 'int',
                nullable: false,
                tableName: 'post',
                columnName: 'id',
            ),
            new ColumnDescription(
                name: 'p_title',
                dbType: 'varchar',
                nullable: false,
                tableName: 'post',
                columnName: 'title',
            ),
            new ColumnDescription(
                name: 'p_h1',
                dbType: 'varchar',
                nullable: true,
                tableName: 'post',
                columnName: 'h1',
            ),
            new ColumnDescription(
                name: 'p_content',
                dbType: 'text',
                nullable: false,
                tableName: 'post',
                columnName: 'content',
            ),
            new ColumnDescription(
                name: 'p_date_creation',
                dbType: 'date',
                nullable: false,
                tableName: 'post',
                columnName: 'date_creation',
            ),
            new ColumnDescription(
                name: 'u_id',
                dbType: 'int',
                nullable: true,
                tableName: 'user',
                columnName: 'id',
            ),
            new ColumnDescription(
                name: 'u_email',
                dbType: 'varchar',
                nullable: true,
                tableName: 'user',
                columnName: 'email',
            ),
        ], $description->getColumns());
    }

    public function testDescribeQueryWithErrorInSql(): void
    {
        $describer = new MysqlDescriber(
            typeMapper: new TypeMapper(),
            connectionInfo: DatabaseManager::mysqliDatabaseConfig()
        );

        $this->assertException(function (\Throwable $e): void {
            $this->assertInstanceOf(PrepareException::class, $e);
            $this->assertSame(
                <<<'SQL'
                        SELECT *,
                        FROM post p
                    SQL,
                $e->getSql(),
            );
            $this->assertSame("You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM post p' at line 2", $e->getDatabaseError());
        }, function () use ($describer): void {
            $describer->describeQuery(
                <<<'SQL'
                        SELECT *,
                        FROM post p
                    SQL
            );
        });

        $this->assertException(function (\Throwable $e): void {
            $this->assertInstanceOf(PrepareException::class, $e);
            $this->assertSame(
                <<<'SQL'
                        SELECT *
                        FROM unknown_table p
                    SQL,
                $e->getSql(),
            );
            $this->assertSame("Table 'sqlx.unknown_table' doesn't exist", $e->getDatabaseError());
        }, function () use ($describer): void {
            $describer->describeQuery(
                <<<'SQL'
                        SELECT *
                        FROM unknown_table p
                    SQL
            );
        });
    }
}

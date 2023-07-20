<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

use JmvDevelop\Sqlx\Exception\SqlxException;
use JmvDevelop\Sqlx\ParameterDescription;
use JmvDevelop\Sqlx\ParameterParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(ParameterParser::class)]
#[UsesClass(ParameterDescription::class)]
final class ParameterParserTest extends TestCase
{
    use AssertExceptionTrait;

    public function testExtractParameters(): void
    {
        $parser = new ParameterParser();
        $this->assertEquals([
            new ParameterDescription(name: 'category_name', dbalTypeName: 'string', nullable: false),
            new ParameterDescription(name: 'post_h1', dbalTypeName: 'string', nullable: true),
            new ParameterDescription(name: 'post_id', dbalTypeName: 'integer', nullable: true),
        ], $parser->extractParameters(
            <<<'SQL'
                select *
                from `post` p
                left join category c on p.category_id = c.id
                where (
                    c.name = :category_name
                    AND (
                        (:post_id IS NOT NULL AND p.id = :post_id) 
                        OR (:post_h1 IS NULL)
                    )
                    AND (
                        (:post_h1 IS NULL AND p.h1 IS NULL) 
                        OR (:post_h1 IS NOT NULL AND p.h1 = :post_h1)
                    )
                ) 
                limit 100

                -- @param string         :category_name
                -- @param string|null    :post_h1
                -- @param null|integer   :post_id
                SQL
        ));
    }

    public function testExtractParametersWithNoParameters(): void
    {
        $parser = new ParameterParser();
        $this->assertEquals([
        ], $parser->extractParameters(
            <<<'SQL'
                select *
                from `post` p
                SQL
        ));
    }

    public function testExtractParametersWithException(): void
    {
        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame("The parameter 'post_id' is not used in the query", $t->getMessage());
        }, function (): void {
            $parser = new ParameterParser();
            $parser->extractParameters(
                <<<'SQL'
                        select *
                        from `post` p
                        limit 100
                        -- @param integer :post_id
                    SQL
            );
        });

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame("The type of parameter 'post_h1' is not defined", $t->getMessage());
        }, function (): void {
            $parser = new ParameterParser();
            $parser->extractParameters(
                <<<'SQL'
                        select *
                        from `post` p
                        where p.id = :post_id and p.h1 = :post_h1
                        limit 100
                        -- @param integer :post_id
                    SQL
            );
        });

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertStringStartsWith('Positional parameters are not supported :', $t->getMessage());
        }, function (): void {
            $parser = new ParameterParser();
            $parser->extractParameters(
                <<<'SQL'
                        select *
                        from `post` p
                        where p.id = :post_id and p.h1 = ?
                        limit 100
                        -- @param integer :post_id
                    SQL
            );
        });

        $this->assertException(function (\Throwable $t): void {
            $this->assertInstanceOf(SqlxException::class, $t);
            $this->assertSame("Invalid type 'integer|string|null'", $t->getMessage());
        }, function (): void {
            $parser = new ParameterParser();
            $parser->extractParameters(
                <<<'SQL'
                        select *
                        from `post` p
                        where p.id = :post_id
                        limit 100
                        -- @param integer|string|null :post_id
                    SQL
            );
        });
    }
}

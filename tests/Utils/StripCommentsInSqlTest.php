<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Utils;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function JmvDevelop\Sqlx\Utils\stripCommentsInSql;
use function Psl\Str\trim;

/** @internal */
#[CoversFunction('JmvDevelop\\Sqlx\\Utils\\stripCommentsInSql')]
final class StripCommentsInSqlTest extends TestCase
{
    public static function provideTest(): iterable
    {
        yield [
            'SELECT * FROM post WHERE id = :id',
            'SELECT * FROM post WHERE id = :id
-- @param integer :id',
        ];

        yield [
            'SELECT * FROM post WHERE id = 1',
            'SELECT * FROM post WHERE id = 1',
        ];
    }

    #[DataProvider('provideTest')]
    public function test(string $expected, string $sql): void
    {
        $this->assertEquals(trim($expected), trim(stripCommentsInSql($sql)));
    }
}

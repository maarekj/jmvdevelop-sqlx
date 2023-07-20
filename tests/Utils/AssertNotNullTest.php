<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Utils;

use JmvDevelop\Sqlx\Tests\AssertExceptionTrait;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use function JmvDevelop\Sqlx\Utils\assertNotNull;

/** @internal */
#[CoversFunction('JmvDevelop\\Sqlx\\Utils\\assertNotNull')]
final class AssertNotNullTest extends TestCase
{
    use AssertExceptionTrait;

    public function testWithNotNullValue(): void
    {
        $this->assertSame('string', assertNotNull('string'));
        $this->assertSame(1, assertNotNull(1));
        $this->assertSame($obj = new \stdClass(), assertNotNull($obj));
    }

    public function testWithNullValue(): void
    {
        $this->assertException(\RuntimeException::class, function (): void {
            assertNotNull(null);
        });
    }
}

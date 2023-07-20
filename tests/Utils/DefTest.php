<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Utils;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use function JmvDevelop\Sqlx\Utils\strDef;

/**
 * @internal
 */
#[CoversFunction('JmvDevelop\\Sqlx\\Utils\\strDef')]
final class DefTest extends TestCase
{
    public function testStrDef(): void
    {
        $this->assertSame('string', strDef('string', 'default'));
        $this->assertSame('default', strDef(null, 'default'));
    }
}

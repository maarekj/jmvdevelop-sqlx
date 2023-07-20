<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Runtime;

use JmvDevelop\Sqlx\Runtime\DefaultValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(DefaultValue::class)]
final class DefaultValueTest extends TestCase
{
    public function test(): void
    {
        $this->assertEquals(new DefaultValue(), new DefaultValue());
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Utils;

use Doctrine\DBAL\SQL\Parser;
use JmvDevelop\Sqlx\Utils\ConvertParameters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(ConvertParameters::class)]
final class ConvertParametersTest extends TestCase
{
    public function testWithPositionnal(): void
    {
        $parser = new Parser(true);
        $parser->parse('SELECT * FROM company WHERE id = ? AND name = ?', $visitor = new ConvertParameters());

        $this->assertSame('SELECT * FROM company WHERE id = ? AND name = ?', $visitor->getSQL());
        $this->assertSame([1 => 1, 2 => 2], $visitor->getParameterMap());
    }

    public function testWithNamed(): void
    {
        $parser = new Parser(true);
        $parser->parse('SELECT * FROM company WHERE id = :id AND name = :name', $visitor = new ConvertParameters());

        $this->assertSame('SELECT * FROM company WHERE id = ? AND name = ?', $visitor->getSQL());
        $this->assertSame([':id' => 1, ':name' => 2], $visitor->getParameterMap());
    }

    public function testWithMixed(): void
    {
        $parser = new Parser(true);
        $parser->parse('SELECT * FROM company WHERE id = ? AND name = :name AND id = ? AND name = :name2', $visitor = new ConvertParameters());

        $this->assertSame('SELECT * FROM company WHERE id = ? AND name = ? AND id = ? AND name = ?', $visitor->getSQL());
        $this->assertSame([1 => 1, ':name' => 2, 3 => 3, ':name2' => 4], $visitor->getParameterMap());
    }
}

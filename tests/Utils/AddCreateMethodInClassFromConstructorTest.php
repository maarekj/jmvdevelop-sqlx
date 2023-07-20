<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Utils;

use Nette\PhpGenerator\ClassType;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use function JmvDevelop\Sqlx\Utils\addCreateMethodInClassFromConstructor;
use function Psl\Str\join;
use function Psl\Str\trim;

/** @internal */
#[CoversFunction('JmvDevelop\\Sqlx\\Utils\\addCreateMethodInClassFromConstructor')]
final class AddCreateMethodInClassFromConstructorTest extends TestCase
{
    public function test(): void
    {
        $class = new ClassType(name: 'MyClass');
        $construct = $class->addMethod('construct');

        $construct->addComment('Comment of construct');
        $construct->addPromotedParameter('param1')->setReadOnly()->setType('array')->addComment('@param mixed[] $param1');
        $construct->addPromotedParameter('param2')->setType('string')->setNullable()->setDefaultValue('default value');
        $construct->addPromotedParameter('param3');

        addCreateMethodInClassFromConstructor(class: $class, construct: $construct, createName: 'create');

        $this->assertSame(
            $this->normalizeString(
                <<<'PHP'
                    class MyClass
                    {
                        /**
                         * Comment of construct
                         */
                    	public function construct(
                    		/** @param mixed[] $param1 */
                    		public readonly array $param1,
                    		public ?string $param2 = 'default value',
                    		public $param3,
                    	) {
                    	}

                    	/**
                         * Comment of construct
                    	 * @param mixed[] $param1
                    	 */
                    	public static function create(array $param1, ?string $param2 = 'default value', $param3): self
                    	{
                    		return new self(
                    		    param1: $param1,
                    		    param2: $param2,
                    		    param3: $param3,
                    		);
                    	}
                    }
                    PHP
            ),
            $this->normalizeString($class->__toString())
        );
    }

    private function normalizeString(string $string): string
    {
        $string = trim($string);
        $lines = explode("\n", $string);

        $normalizedLines = [];
        foreach ($lines as $line) {
            $normalized = preg_replace("/\t/", '    ', trim($line));
            if ('' !== $normalized) {
                $normalizedLines[] = $normalized;
            }
        }

        return join($normalizedLines, "\n");
    }
}

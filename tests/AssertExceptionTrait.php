<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

trait AssertExceptionTrait
{
    /**
     * @param null|\Closure(\Throwable):void|string $asserter
     * @param \Closure():void                       $callable
     */
    public function assertException(\Closure|string|null $asserter, \Closure $callable): void
    {
        if (null === $asserter) {
            $asserter = \Throwable::class;
        }

        if (\is_string($asserter)) {
            $asserter = function (\Throwable $e) use ($asserter): void {
                $this->assertInstanceOf($asserter, $e);
            };
        }

        $exception = null;

        try {
            $callable();
        } catch (\Throwable $t) {
            $exception = $t;
        }

        if (null === $exception) {
            $this->assertTrue(false, 'Exception not thrown.');
        } else {
            $asserter($exception);
        }
    }
}

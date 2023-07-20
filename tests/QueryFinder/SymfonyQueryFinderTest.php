<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\QueryFinder;

use JmvDevelop\Sqlx\QueryFinder\Source;
use JmvDevelop\Sqlx\QueryFinder\SymfonyQueryFinder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @internal */
#[CoversClass(SymfonyQueryFinder::class)]
#[UsesClass(Source::class)]
final class SymfonyQueryFinderTest extends TestCase
{
    public function testFindQueriesOnExamples(): void
    {
        $finder = (new Finder())->in(__DIR__.'/../../examples')->name('*.sql');
        $queryFinder = new SymfonyQueryFinder(finder: $finder);

        $sources = [];
        foreach ($queryFinder->findQueries() as $source) {
            $sources[$source->getName()] = $source;
        }

        $this->assertCount(7, $sources);

        $this->assertSame('get_all_posts', $sources['get_all_posts']->getName());
        $this->assertSame(file_get_contents(__DIR__.'/../../examples/sql/get_all_posts.sql'), $sources['get_all_posts']->getContent());
        $this->assertSame(realpath(__DIR__.'/../../examples/sql/get_all_posts.sql'), $sources['get_all_posts']->getRealPath());

        $this->assertSame('get_all_users', $sources['get_all_users']->getName());
        $this->assertSame(file_get_contents(__DIR__.'/../../examples/sql/get_all_users.sql'), $sources['get_all_users']->getContent());
        $this->assertSame(realpath(__DIR__.'/../../examples/sql/get_all_users.sql'), $sources['get_all_users']->getRealPath());

        $this->assertSame('get_all_posts', $sources['get_all_posts']->getName());
        $this->assertSame(file_get_contents(__DIR__.'/../../examples/sql/get_all_posts.sql'), $sources['get_all_posts']->getContent());
        $this->assertSame(realpath(__DIR__.'/../../examples/sql/get_all_posts.sql'), $sources['get_all_posts']->getRealPath());
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\QueryFinder;

use Symfony\Component\Finder\Finder;
use function Psl\Filesystem\get_filename;

final class SymfonyQueryFinder implements QueryFinder
{
    public function __construct(private readonly Finder $finder)
    {
    }

    /** @return iterable<Source> */
    public function findQueries(): iterable
    {
        $files = $this->finder->getIterator();

        foreach ($files as $file) {
            $realPath = $file->getRealPath();
            if (false !== $realPath && '' !== $realPath) {
                $name = get_filename($realPath);
                $content = file_get_contents($realPath);
                if (false !== $content) {
                    yield new Source(name: $name, realPath: $realPath, content: $content);
                }
            }
        }
    }
}

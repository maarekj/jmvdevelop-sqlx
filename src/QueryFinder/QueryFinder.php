<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\QueryFinder;

interface QueryFinder
{
    /** @return iterable<Source> */
    public function findQueries(): iterable;
}

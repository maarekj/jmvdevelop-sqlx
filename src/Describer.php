<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

interface Describer
{
    public function describeQuery(string $sql): QueryDescription;
}

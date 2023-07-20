<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

final class QueryDescription
{
    /**
     * @param list<ColumnDescription> $columns
     */
    public function __construct(
        private readonly string $sql,
        private readonly array $columns,
    ) {
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    /** @return list<ColumnDescription> */
    public function getColumns(): array
    {
        return $this->columns;
    }
}

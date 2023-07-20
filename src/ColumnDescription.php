<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

final class ColumnDescription
{
    public function __construct(
        private readonly string $name,
        private readonly string $dbType,
        private readonly bool $nullable,
        private readonly ?string $tableName,
        private readonly ?string $columnName,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDbType(): string
    {
        return $this->dbType;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getColumnName(): ?string
    {
        return $this->columnName;
    }
}

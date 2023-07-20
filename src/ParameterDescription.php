<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

final class ParameterDescription
{
    public function __construct(
        private readonly string $name,
        private readonly string $dbalTypeName,
        private readonly bool $nullable,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDbalTypeName(): string
    {
        return $this->dbalTypeName;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class FindCategoryByIdRow
{
    public function __construct(
        /** @param (int) $id */
        public int $id,
        /** @param (string) $name */
        public string $name,
    ) {
    }

    /**
     * @param (int)    $id
     * @param (string) $name
     */
    public static function create(int $id, string $name): self
    {
        return new self(
            id: $id,
            name: $name,
        );
    }
}

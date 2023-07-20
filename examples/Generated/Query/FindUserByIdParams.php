<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class FindUserByIdParams
{
    public function __construct(
        /** @param (int) $id */
        public int $id,
    ) {
    }

    /**
     * @param (int) $id
     */
    public function withId(int $id): self
    {
        return new self(
            id: $id,
        );
    }

    /**
     * @param (int) $id
     */
    public static function create(int $id): self
    {
        return new self(
            id: $id,
        );
    }
}

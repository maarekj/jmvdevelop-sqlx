<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class FindUserParams
{
    public function __construct(
        /** @param null|(int) $id */
        public int|null $id,
        /** @param null|(string) $email */
        public string|null $email,
        /** @param null|(string) $name */
        public string|null $name,
    ) {
    }

    /**
     * @param null|(int) $id
     */
    public function withId(int|null $id): self
    {
        return new self(
            id: $id,
            email: $this->email,
            name: $this->name,
        );
    }

    /**
     * @param null|(string) $email
     */
    public function withEmail(string|null $email): self
    {
        return new self(
            id: $this->id,
            email: $email,
            name: $this->name,
        );
    }

    /**
     * @param null|(string) $name
     */
    public function withName(string|null $name): self
    {
        return new self(
            id: $this->id,
            email: $this->email,
            name: $name,
        );
    }

    /**
     * @param null|(int)    $id
     * @param null|(string) $email
     * @param null|(string) $name
     */
    public static function create(int|null $id, string|null $email, string|null $name): self
    {
        return new self(
            id: $id,
            email: $email,
            name: $name,
        );
    }
}

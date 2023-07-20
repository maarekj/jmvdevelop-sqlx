<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class FindUserByIdRow
{
    public function __construct(
        /** @param (int) $id */
        public int $id,
        /** @param (string) $name */
        public string $name,
        /** @param (string) $password */
        public string $password,
        /** @param (string) $email */
        public string $email,
        /** @param (\DateTimeImmutable) $date_inscription */
        public \DateTimeImmutable $date_inscription,
    ) {
    }

    /**
     * @param (int)                $id
     * @param (string)             $name
     * @param (string)             $password
     * @param (string)             $email
     * @param (\DateTimeImmutable) $date_inscription
     */
    public static function create(
        int $id,
        string $name,
        string $password,
        string $email,
        \DateTimeImmutable $date_inscription,
    ): self {
        return new self(
            id: $id,
            name: $name,
            password: $password,
            email: $email,
            date_inscription: $date_inscription,
        );
    }
}

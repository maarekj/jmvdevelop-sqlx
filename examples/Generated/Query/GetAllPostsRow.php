<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class GetAllPostsRow
{
    public function __construct(
        /** @param (int) $id */
        public int $id,
        /** @param (int) $author_id */
        public int $author_id,
        /** @param (int) $category_id */
        public int $category_id,
        /** @param (string) $title */
        public string $title,
        /** @param (string) $content */
        public string $content,
        /** @param (\DateTimeImmutable) $date_creation */
        public \DateTimeImmutable $date_creation,
        /** @param null|(string) $h1 */
        public string|null $h1,
    ) {
    }

    /**
     * @param (int)                $id
     * @param (int)                $author_id
     * @param (int)                $category_id
     * @param (string)             $title
     * @param (string)             $content
     * @param (\DateTimeImmutable) $date_creation
     * @param null|(string)        $h1
     */
    public static function create(
        int $id,
        int $author_id,
        int $category_id,
        string $title,
        string $content,
        \DateTimeImmutable $date_creation,
        string|null $h1,
    ): self {
        return new self(
            id: $id,
            author_id: $author_id,
            category_id: $category_id,
            title: $title,
            content: $content,
            date_creation: $date_creation,
            h1: $h1,
        );
    }
}

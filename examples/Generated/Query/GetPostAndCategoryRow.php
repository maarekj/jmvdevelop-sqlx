<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final readonly class GetPostAndCategoryRow
{
    public function __construct(
        /** @param (int) $p_id */
        public int $p_id,
        /** @param (string) $p_title */
        public string $p_title,
        /** @param (string) $p_content */
        public string $p_content,
        /** @param null|(int) $c_id */
        public int|null $c_id,
        /** @param null|(string) $c_name */
        public string|null $c_name,
    ) {
    }

    /**
     * @param (int)         $p_id
     * @param (string)      $p_title
     * @param (string)      $p_content
     * @param null|(int)    $c_id
     * @param null|(string) $c_name
     */
    public static function create(
        int $p_id,
        string $p_title,
        string $p_content,
        int|null $c_id,
        string|null $c_name,
    ): self {
        return new self(
            p_id: $p_id,
            p_title: $p_title,
            p_content: $p_content,
            c_id: $c_id,
            c_name: $c_name,
        );
    }
}

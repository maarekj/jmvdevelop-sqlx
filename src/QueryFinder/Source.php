<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\QueryFinder;

final class Source
{
    public function __construct(
        private readonly string $name,
        private readonly string $realPath,
        private readonly string $content,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRealPath(): string
    {
        return $this->realPath;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

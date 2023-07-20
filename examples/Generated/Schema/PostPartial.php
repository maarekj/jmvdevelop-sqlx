<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class PostPartial
{
    public const TABLE_NAME = 'post';

    public function __construct(
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $author_id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $category_id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $content = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_creation = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private string|null|\JmvDevelop\Sqlx\Runtime\DefaultValue $h1 = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $title = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $author_id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $category_id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $content = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_creation = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        string|null|\JmvDevelop\Sqlx\Runtime\DefaultValue $h1 = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $title = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ): self {
        return new self(
            author_id: $author_id,
            category_id: $category_id,
            content: $content,
            date_creation: $date_creation,
            h1: $h1,
            id: $id,
            title: $title,
        );
    }

    public function getAuthorId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->author_id;
    }

    public function getCategoryId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->category_id;
    }

    public function getContent(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->content;
    }

    public function getDateCreation(): \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->date_creation;
    }

    public function getH1(): string|null|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->h1;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function getTitle(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->title;
    }

    public function withAuthorId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $author_id): static
    {
        return new self(
            author_id: $author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function withCategoryId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $category_id): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function withContent(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $content): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function withDateCreation(\DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_creation): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function withH1(string|null|\JmvDevelop\Sqlx\Runtime\DefaultValue $h1): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function withId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $id,
            title: $this->title,
        );
    }

    public function withTitle(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $title): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $title,
        );
    }

    public function clone(): static
    {
        return new static(
            author_id: $this->author_id,
            category_id: $this->category_id,
            content: $this->content,
            date_creation: $this->date_creation,
            h1: $this->h1,
            id: $this->id,
            title: $this->title,
        );
    }

    public function toArray(): array
    {
        $return = [];
        if (false === ($this->author_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['author_id'] = $this->author_id;
        }
        if (false === ($this->category_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['category_id'] = $this->category_id;
        }
        if (false === ($this->content instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['content'] = $this->content;
        }
        if (false === ($this->date_creation instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['date_creation'] = $this->date_creation;
        }
        if (false === ($this->h1 instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['h1'] = $this->h1;
        }
        if (false === ($this->id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['id'] = $this->id;
        }
        if (false === ($this->title instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['title'] = $this->title;
        }

        return $return;
    }

    /**
     * @return array<string, \Doctrine\DBAL\Types\Type>
     */
    public function doctrineTypes(): array
    {
        $types = [];
        if (false === ($this->author_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['author_id'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\IntegerType::class, [
            ]);
        }
        if (false === ($this->category_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['category_id'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\IntegerType::class, [
            ]);
        }
        if (false === ($this->content instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['content'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\TextType::class, [
            ]);
        }
        if (false === ($this->date_creation instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['date_creation'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\DateType::class, [
            ]);
        }
        if (false === ($this->h1 instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['h1'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\StringType::class, [
            ]);
        }
        if (false === ($this->id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['id'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\IntegerType::class, [
            ]);
        }
        if (false === ($this->title instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['title'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\StringType::class, [
            ]);
        }

        return $types;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function update(\Doctrine\DBAL\Connection $connection, PostCriteria $criteria): int
    {
        $row = $this->toArray();
        $types = array_merge([], $this->doctrineTypes(), $criteria->doctrineTypes());

        return $connection->update(table: self::TABLE_NAME, data: $row, criteria: $criteria->toArray(), types: $types);
    }
}

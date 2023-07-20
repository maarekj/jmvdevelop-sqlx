<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class CommentObject
{
    public const TABLE_NAME = 'comment';

    public function __construct(
        private int $author_id,
        private int $post_id,
        private string $content,
        private \DateTimeInterface $date_creation,
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        int $author_id,
        int $post_id,
        string $content,
        \DateTimeInterface $date_creation,
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ): self {
        return new self(
            author_id: $author_id,
            post_id: $post_id,
            content: $content,
            date_creation: $date_creation,
            id: $id,
        );
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->date_creation;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function withAuthorId(int $author_id): static
    {
        return new self(
            author_id: $author_id,
            post_id: $this->post_id,
            content: $this->content,
            date_creation: $this->date_creation,
            id: $this->id,
        );
    }

    public function withPostId(int $post_id): static
    {
        return new static(
            author_id: $this->author_id,
            post_id: $post_id,
            content: $this->content,
            date_creation: $this->date_creation,
            id: $this->id,
        );
    }

    public function withContent(string $content): static
    {
        return new static(
            author_id: $this->author_id,
            post_id: $this->post_id,
            content: $content,
            date_creation: $this->date_creation,
            id: $this->id,
        );
    }

    public function withDateCreation(\DateTimeInterface $date_creation): static
    {
        return new static(
            author_id: $this->author_id,
            post_id: $this->post_id,
            content: $this->content,
            date_creation: $date_creation,
            id: $this->id,
        );
    }

    public function withId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id): static
    {
        return new static(
            author_id: $this->author_id,
            post_id: $this->post_id,
            content: $this->content,
            date_creation: $this->date_creation,
            id: $id,
        );
    }

    public function clone(): static
    {
        return new static(
            author_id: $this->author_id,
            post_id: $this->post_id,
            content: $this->content,
            date_creation: $this->date_creation,
            id: $this->id,
        );
    }

    public function toArray(): array
    {
        $return = [];
        if (false === ($this->author_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['author_id'] = $this->author_id;
        }
        if (false === ($this->post_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['post_id'] = $this->post_id;
        }
        if (false === ($this->content instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['content'] = $this->content;
        }
        if (false === ($this->date_creation instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['date_creation'] = $this->date_creation;
        }
        if (false === ($this->id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['id'] = $this->id;
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
        if (false === ($this->post_id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['post_id'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\IntegerType::class, [
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
        if (false === ($this->id instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['id'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\IntegerType::class, [
            ]);
        }

        return $types;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function insert(\Doctrine\DBAL\Connection $connection): int
    {
        $row = $this->toArray();
        $types = $this->doctrineTypes();

        return $connection->insert(self::TABLE_NAME, $row, $types);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function insertAndLastIntId(\Doctrine\DBAL\Connection $connection): int
    {
        $this->insert(connection: $connection);

        return (int) $connection->lastInsertId();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function insertAndLastStringId(\Doctrine\DBAL\Connection $connection): string
    {
        $this->insert(connection: $connection);

        return (string) $connection->lastInsertId();
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class CategoryObject
{
    public const TABLE_NAME = 'category';

    public function __construct(
        private string $name,
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        string $name,
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ): self {
        return new self(
            name: $name,
            id: $id,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function withName(string $name): static
    {
        return new self(
            name: $name,
            id: $this->id,
        );
    }

    public function withId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id): static
    {
        return new static(
            name: $this->name,
            id: $id,
        );
    }

    public function clone(): static
    {
        return new static(
            name: $this->name,
            id: $this->id,
        );
    }

    public function toArray(): array
    {
        $return = [];
        if (false === ($this->name instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['name'] = $this->name;
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
        if (false === ($this->name instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['name'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\StringType::class, [
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

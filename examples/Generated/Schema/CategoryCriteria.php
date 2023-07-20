<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class CategoryCriteria
{
    public const TABLE_NAME = 'category';

    public function __construct(
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ): self {
        return new self(
            name: $name,
            id: $id,
        );
    }

    public function getName(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->name;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function withName(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name): static
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
}

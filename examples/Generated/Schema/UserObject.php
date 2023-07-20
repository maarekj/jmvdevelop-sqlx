<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class UserObject
{
    public const TABLE_NAME = 'user';

    public function __construct(
        private string $name,
        private string $password,
        private string $email,
        private \DateTimeInterface $date_inscription,
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        string $name,
        string $password,
        string $email,
        \DateTimeInterface $date_inscription,
        int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ): self {
        return new self(
            name: $name,
            password: $password,
            email: $email,
            date_inscription: $date_inscription,
            id: $id,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDateInscription(): \DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function withName(string $name): static
    {
        return new self(
            name: $name,
            password: $this->password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withPassword(string $password): static
    {
        return new static(
            name: $this->name,
            password: $password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withEmail(string $email): static
    {
        return new static(
            name: $this->name,
            password: $this->password,
            email: $email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withDateInscription(\DateTimeInterface $date_inscription): static
    {
        return new static(
            name: $this->name,
            password: $this->password,
            email: $this->email,
            date_inscription: $date_inscription,
            id: $this->id,
        );
    }

    public function withId(int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id): static
    {
        return new static(
            name: $this->name,
            password: $this->password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $id,
        );
    }

    public function clone(): static
    {
        return new static(
            name: $this->name,
            password: $this->password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function toArray(): array
    {
        $return = [];
        if (false === ($this->name instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['name'] = $this->name;
        }
        if (false === ($this->password instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['password'] = $this->password;
        }
        if (false === ($this->email instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['email'] = $this->email;
        }
        if (false === ($this->date_inscription instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $return['date_inscription'] = $this->date_inscription;
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
        if (false === ($this->password instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['password'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\StringType::class, [
            ]);
        }
        if (false === ($this->email instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['email'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\StringType::class, [
            ]);
        }
        if (false === ($this->date_inscription instanceof \JmvDevelop\Sqlx\Runtime\DefaultValue)) {
            $types['date_inscription'] = \Nette\PhpGenerator\Dumper::createObject(\Doctrine\DBAL\Types\DateType::class, [
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

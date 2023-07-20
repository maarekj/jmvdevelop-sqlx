<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Schema;

final class UserCriteria
{
    public const TABLE_NAME = 'user';

    public function __construct(
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $password = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private string|\JmvDevelop\Sqlx\Runtime\DefaultValue $email = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_inscription = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        private int|\JmvDevelop\Sqlx\Runtime\DefaultValue $id = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
    ) {
    }

    public static function create(
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $password = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        string|\JmvDevelop\Sqlx\Runtime\DefaultValue $email = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
        \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_inscription = new \JmvDevelop\Sqlx\Runtime\DefaultValue(),
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

    public function getName(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->name;
    }

    public function getPassword(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->password;
    }

    public function getEmail(): string|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->email;
    }

    public function getDateInscription(): \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->date_inscription;
    }

    public function getId(): int|\JmvDevelop\Sqlx\Runtime\DefaultValue
    {
        return $this->id;
    }

    public function withName(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $name): static
    {
        return new self(
            name: $name,
            password: $this->password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withPassword(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $password): static
    {
        return new static(
            name: $this->name,
            password: $password,
            email: $this->email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withEmail(string|\JmvDevelop\Sqlx\Runtime\DefaultValue $email): static
    {
        return new static(
            name: $this->name,
            password: $this->password,
            email: $email,
            date_inscription: $this->date_inscription,
            id: $this->id,
        );
    }

    public function withDateInscription(
        \DateTimeInterface|\JmvDevelop\Sqlx\Runtime\DefaultValue $date_inscription,
    ): static {
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
}

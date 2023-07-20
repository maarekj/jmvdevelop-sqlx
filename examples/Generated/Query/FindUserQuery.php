<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final class FindUserQuery
{
    public const content = "select *\nfrom `user` u\nwhere\n    (:id IS NOT NULL AND u.id = :id OR :id IS NULL)\n    AND (:email IS NOT NULL AND u.email = :email OR :email IS NULL)\n    AND (:name IS NOT NULL AND u.name = :name OR :name IS NULL)\n\n-- @param integer|null :id\n-- @param string|null :email\n-- @param string|null :name";
    public const sql = "select *\nfrom `user` u\nwhere\n    (:id IS NOT NULL AND u.id = :id OR :id IS NULL)\n    AND (:email IS NOT NULL AND u.email = :email OR :email IS NULL)\n    AND (:name IS NOT NULL AND u.name = :name OR :name IS NULL)\n\n\n\n";

    public function __construct(
        private readonly \Doctrine\DBAL\Connection $connection,
        private readonly FindUserParams $params,
    ) {
    }

    public static function create(\Doctrine\DBAL\Connection $connection, FindUserParams $params): self
    {
        return new self(
            connection: $connection,
            params: $params,
        );
    }

    public function describe(): \JmvDevelop\Sqlx\QueryDescription
    {
        return \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\QueryDescription::class, [
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00sql" => "select *\nfrom `user` u\nwhere\n    (:id IS NOT NULL AND u.id = :id OR :id IS NULL)\n    AND (:email IS NOT NULL AND u.email = :email OR :email IS NULL)\n    AND (:name IS NOT NULL AND u.name = :name OR :name IS NULL)\n\n-- @param integer|null :id\n-- @param string|null :email\n-- @param string|null :name",
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00columns" => [
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'user',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'name',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'user',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'name',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'password',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'user',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'password',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'email',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'user',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'email',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'date_inscription',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'date',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'user',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'date_inscription',
                ]),
            ],
        ]);
    }

    /**
     * @return \JmvDevelop\Sqlx\Runtime\Result<array-key, \JmvDevelop\Sqlx\Examples\Generated\Query\FindUserRow>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(): \JmvDevelop\Sqlx\Runtime\Result
    {
        $stmt = $this->connection->prepare(self::sql);

        $stmt->bindValue(param: 'id', value: $this->params->id, type: 'integer');
        $stmt->bindValue(param: 'email', value: $this->params->email, type: 'string');
        $stmt->bindValue(param: 'name', value: $this->params->name, type: 'string');

        return new \JmvDevelop\Sqlx\Runtime\Result(
            result: $stmt->executeQuery(),
            parseRow: fn (array $row) => $this->parseRow(row: $row, connection: $this->connection),
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function parseRow(array $row, \Doctrine\DBAL\Connection $connection): FindUserRow
    {
        return new FindUserRow(
            id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['id'], platform: $connection->getDatabasePlatform()),
            name: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['name'], platform: $connection->getDatabasePlatform()),
            password: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['password'], platform: $connection->getDatabasePlatform()),
            email: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['email'], platform: $connection->getDatabasePlatform()),
            date_inscription: \Doctrine\DBAL\Types\Type::getType('date_immutable')->convertToPHPValue(value: $row['date_inscription'], platform: $connection->getDatabasePlatform()),
        );
    }
}

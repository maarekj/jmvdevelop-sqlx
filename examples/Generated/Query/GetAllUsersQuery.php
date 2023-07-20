<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final class GetAllUsersQuery
{
    public const content = "select *\nfrom `user`";
    public const sql = "select *\nfrom `user`";

    public function __construct(
        private readonly \Doctrine\DBAL\Connection $connection,
    ) {
    }

    public static function create(\Doctrine\DBAL\Connection $connection): self
    {
        return new self(
            connection: $connection,
        );
    }

    public function describe(): \JmvDevelop\Sqlx\QueryDescription
    {
        return \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\QueryDescription::class, [
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00sql" => "select *\nfrom `user`",
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
     * @return \JmvDevelop\Sqlx\Runtime\Result<array-key, \JmvDevelop\Sqlx\Examples\Generated\Query\GetAllUsersRow>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(): \JmvDevelop\Sqlx\Runtime\Result
    {
        $stmt = $this->connection->prepare(self::sql);

        return new \JmvDevelop\Sqlx\Runtime\Result(
            result: $stmt->executeQuery(),
            parseRow: fn (array $row) => $this->parseRow(row: $row, connection: $this->connection),
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function parseRow(array $row, \Doctrine\DBAL\Connection $connection): GetAllUsersRow
    {
        return new GetAllUsersRow(
            id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['id'], platform: $connection->getDatabasePlatform()),
            name: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['name'], platform: $connection->getDatabasePlatform()),
            password: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['password'], platform: $connection->getDatabasePlatform()),
            email: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['email'], platform: $connection->getDatabasePlatform()),
            date_inscription: \Doctrine\DBAL\Types\Type::getType('date_immutable')->convertToPHPValue(value: $row['date_inscription'], platform: $connection->getDatabasePlatform()),
        );
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final class FindCategoryByIdQuery
{
    public const content = "select id, name\nfrom `category` c\nwhere c.id = :id\n\n-- @param integer :id";
    public const sql = "select id, name\nfrom `category` c\nwhere c.id = :id\n\n";

    public function __construct(
        private readonly \Doctrine\DBAL\Connection $connection,
        private readonly FindCategoryByIdParams $params,
    ) {
    }

    public static function create(\Doctrine\DBAL\Connection $connection, FindCategoryByIdParams $params): self
    {
        return new self(
            connection: $connection,
            params: $params,
        );
    }

    public function describe(): \JmvDevelop\Sqlx\QueryDescription
    {
        return \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\QueryDescription::class, [
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00sql" => "select id, name\nfrom `category` c\nwhere c.id = :id\n\n-- @param integer :id",
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00columns" => [
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'category',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'name',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'category',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'name',
                ]),
            ],
        ]);
    }

    /**
     * @return \JmvDevelop\Sqlx\Runtime\Result<array-key, \JmvDevelop\Sqlx\Examples\Generated\Query\FindCategoryByIdRow>
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(): \JmvDevelop\Sqlx\Runtime\Result
    {
        $stmt = $this->connection->prepare(self::sql);

        $stmt->bindValue(param: 'id', value: $this->params->id, type: 'integer');

        return new \JmvDevelop\Sqlx\Runtime\Result(
            result: $stmt->executeQuery(),
            parseRow: fn (array $row) => $this->parseRow(row: $row, connection: $this->connection),
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function parseRow(array $row, \Doctrine\DBAL\Connection $connection): FindCategoryByIdRow
    {
        return new FindCategoryByIdRow(
            id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['id'], platform: $connection->getDatabasePlatform()),
            name: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['name'], platform: $connection->getDatabasePlatform()),
        );
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final class GetAllPostsQuery
{
    public const content = "select *\nfrom post";
    public const sql = "select *\nfrom post";

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
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00sql" => "select *\nfrom post",
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00columns" => [
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'author_id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'author_id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'category_id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'category_id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'title',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'title',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'content',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'text',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'content',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'date_creation',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'date',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'date_creation',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'h1',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => true,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'h1',
                ]),
            ],
        ]);
    }

    /**
     * @return \JmvDevelop\Sqlx\Runtime\Result<array-key, \JmvDevelop\Sqlx\Examples\Generated\Query\GetAllPostsRow>
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
    public static function parseRow(array $row, \Doctrine\DBAL\Connection $connection): GetAllPostsRow
    {
        return new GetAllPostsRow(
            id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['id'], platform: $connection->getDatabasePlatform()),
            author_id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['author_id'], platform: $connection->getDatabasePlatform()),
            category_id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['category_id'], platform: $connection->getDatabasePlatform()),
            title: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['title'], platform: $connection->getDatabasePlatform()),
            content: \Doctrine\DBAL\Types\Type::getType('text')->convertToPHPValue(value: $row['content'], platform: $connection->getDatabasePlatform()),
            date_creation: \Doctrine\DBAL\Types\Type::getType('date_immutable')->convertToPHPValue(value: $row['date_creation'], platform: $connection->getDatabasePlatform()),
            h1: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['h1'], platform: $connection->getDatabasePlatform()),
        );
    }
}

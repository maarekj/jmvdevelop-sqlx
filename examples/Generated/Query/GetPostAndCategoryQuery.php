<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Examples\Generated\Query;

final class GetPostAndCategoryQuery
{
    public const content = "select\n    p.id p_id,\n    p.title p_title,\n    p.content p_content,\n    c.id c_id,\n    c.name c_name\nfrom `post` p\nleft join category c on p.category_id = c.id\nlimit 100\n\n#\n# @param int|null \$name";
    public const sql = "select\n    p.id p_id,\n    p.title p_title,\n    p.content p_content,\n    c.id c_id,\n    c.name c_name\nfrom `post` p\nleft join category c on p.category_id = c.id\nlimit 100\n\n#\n# @param int|null \$name";

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
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00sql" => "select\n    p.id p_id,\n    p.title p_title,\n    p.content p_content,\n    c.id c_id,\n    c.name c_name\nfrom `post` p\nleft join category c on p.category_id = c.id\nlimit 100\n\n#\n# @param int|null \$name",
            "\x00JmvDevelop\\Sqlx\\QueryDescription\x00columns" => [
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'p_id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'p_title',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'title',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'p_content',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'text',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => false,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'post',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'content',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'c_id',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'int',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => true,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'category',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'id',
                ]),
                \Nette\PhpGenerator\Dumper::createObject(\JmvDevelop\Sqlx\ColumnDescription::class, [
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00name" => 'c_name',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00dbType" => 'varchar',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00nullable" => true,
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00tableName" => 'category',
                    "\x00JmvDevelop\\Sqlx\\ColumnDescription\x00columnName" => 'name',
                ]),
            ],
        ]);
    }

    /**
     * @return \JmvDevelop\Sqlx\Runtime\Result<array-key, \JmvDevelop\Sqlx\Examples\Generated\Query\GetPostAndCategoryRow>
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
    public static function parseRow(array $row, \Doctrine\DBAL\Connection $connection): GetPostAndCategoryRow
    {
        return new GetPostAndCategoryRow(
            p_id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['p_id'], platform: $connection->getDatabasePlatform()),
            p_title: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['p_title'], platform: $connection->getDatabasePlatform()),
            p_content: \Doctrine\DBAL\Types\Type::getType('text')->convertToPHPValue(value: $row['p_content'], platform: $connection->getDatabasePlatform()),
            c_id: \Doctrine\DBAL\Types\Type::getType('integer')->convertToPHPValue(value: $row['c_id'], platform: $connection->getDatabasePlatform()),
            c_name: \Doctrine\DBAL\Types\Type::getType('string')->convertToPHPValue(value: $row['c_name'], platform: $connection->getDatabasePlatform()),
        );
    }
}

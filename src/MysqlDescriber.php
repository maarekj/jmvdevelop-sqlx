<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

use Doctrine\DBAL\SQL\Parser;
use JmvDevelop\Sqlx\Exception\PrepareException;
use JmvDevelop\Sqlx\Exception\SqlxException;
use JmvDevelop\Sqlx\Utils\ConvertParameters;
use function Psl\Json\encode;
use function Psl\Json\typed;
use function Psl\Type\int;
use function Psl\Type\nullable;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Psl\Type\vec;

final class MysqlDescriber implements Describer
{
    private \mysqli|null $mysqli = null;

    /**
     * @param array{
     *     hostname?: ?string,
     *     username?: ?string,
     *     password?: ?string,
     *     database?: ?string,
     *     port?: ?int,
     *     socket?: ?string,
     * } $connectionInfo
     */
    public function __construct(
        private readonly array $connectionInfo,
        private readonly TypeMapperInterface $typeMapper,
    ) {
    }

    /** @throws PrepareException|SqlxException */
    public function describeQuery(string $sql): QueryDescription
    {
        $mysqli = $this->getMysqli();

        try {
            $stmt = $mysqli->prepare($this->sanitizeSql($sql));
        } catch (\Throwable $t) {
            throw new PrepareException(sql: $sql, databaseError: $t->getMessage(), previous: $t);
        }

        if (false === $stmt) {
            // @codeCoverageIgnoreStart
            throw new PrepareException(sql: $sql, databaseError: $mysqli->error);
            // @codeCoverageIgnoreEnd
        }

        $columns = [];
        $metadata = $stmt->result_metadata();
        if (false === $metadata) {
            // @codeCoverageIgnoreStart
            throw new SqlxException($mysqli->error);
            // @codeCoverageIgnoreEnd
        }

        $fields = typed(encode($metadata->fetch_fields()), vec(shape([
            'name' => string(),
            'orgname' => nullable(string()),
            'table' => nullable(string()),
            'orgtable' => nullable(string()),
            'def' => nullable(string()),
            'max_length' => nullable(int()),
            'length' => nullable(int()),
            'charsetnr' => nullable(int()),
            'flags' => int(),
            'type' => int(),
        ], true)));

        foreach ($fields as $field) {
            $columns[] = new ColumnDescription(
                name: $field['name'],
                dbType: $this->typeMapper->mysqliTypeToDbType($field['type']),
                nullable: !($field['flags'] & \MYSQLI_NOT_NULL_FLAG),
                tableName: $field['orgtable'] ?? null,
                columnName: $field['orgname'] ?? null,
            );
        }

        $metadata->free();
        $stmt->close();

        return new QueryDescription(
            sql: $sql,
            columns: $columns,
        );
    }

    private function getMysqli(): \mysqli
    {
        if (null === $this->mysqli) {
            $this->mysqli = new \mysqli(
                hostname: $this->connectionInfo['hostname'] ?? null,
                username: $this->connectionInfo['username'] ?? null,
                password: $this->connectionInfo['password'] ?? null,
                database: $this->connectionInfo['database'] ?? null,
                port: $this->connectionInfo['port'] ?? null,
                socket: $this->connectionInfo['socket'] ?? null,
            );
        }

        return $this->mysqli;
    }

    private function sanitizeSql(string $sql): string
    {
        $parser = new Parser(true);
        $visitor = new ConvertParameters();
        $parser->parse($sql, $visitor);

        return $visitor->getSQL();
    }
}

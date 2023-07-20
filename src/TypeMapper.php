<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

final class TypeMapper implements TypeMapperInterface
{
    /** @var array<string, string> */
    private array $mysqlDbTypeToDbalNameMapping;

    /** @var array<string, string> */
    private array $dbTypeToDbalNameMapping;

    /** @var array<string, string> */
    private array $dbalNameToPhpMapping;

    /** @var array<string, string> */
    private array $dbalNameToPhpstanMapping;

    public function __construct()
    {
        $this->dbTypeToDbalNameMapping = [];
        $this->mysqlDbTypeToDbalNameMapping = [
            'bigint' => Types::BIGINT,
            'binary' => Types::BINARY,
            'blob' => Types::BLOB,
            'char' => Types::STRING,
            'date' => Types::DATE_IMMUTABLE,
            'datetime' => Types::DATETIME_IMMUTABLE,
            'decimal' => Types::DECIMAL,
            'double' => Types::FLOAT,
            'float' => Types::FLOAT,
            'int' => Types::INTEGER,
            'integer' => Types::INTEGER,
            'longblob' => Types::BLOB,
            'longtext' => Types::TEXT,
            'mediumblob' => Types::BLOB,
            'mediumint' => Types::INTEGER,
            'mediumtext' => Types::TEXT,
            'numeric' => Types::DECIMAL,
            'real' => Types::FLOAT,
            'set' => Types::SIMPLE_ARRAY,
            'smallint' => Types::SMALLINT,
            'string' => Types::STRING,
            'text' => Types::TEXT,
            'time' => Types::TIME_IMMUTABLE,
            'timestamp' => Types::DATETIME_IMMUTABLE,
            'tinyblob' => Types::BLOB,
            'tinyint' => Types::BOOLEAN,
            'tinytext' => Types::TEXT,
            'varbinary' => Types::BINARY,
            'varchar' => Types::STRING,
            'year' => Types::DATE_IMMUTABLE,
        ];

        $this->dbalNameToPhpMapping = [
            Types::ARRAY => 'array',
            Types::ASCII_STRING => 'string',
            Types::BIGINT => 'string',
            Types::BINARY => 'mixed',
            Types::BLOB => 'mixed',
            Types::BOOLEAN => 'bool',
            Types::DATE_MUTABLE => '\\'.\DateTimeInterface::class,
            Types::DATE_IMMUTABLE => '\\'.\DateTimeImmutable::class,
            Types::DATEINTERVAL => '\\'.\DateInterval::class,
            Types::DATETIME_MUTABLE => '\\'.\DateTimeInterface::class,
            Types::DATETIME_IMMUTABLE => '\\'.\DateTimeImmutable::class,
            Types::DATETIMETZ_MUTABLE => '\\'.\DateTimeInterface::class,
            Types::DATETIMETZ_IMMUTABLE => '\\'.\DateTimeImmutable::class,
            Types::DECIMAL => 'string',
            Types::FLOAT => 'float',
            Types::GUID => 'string',
            Types::INTEGER => 'int',
            Types::JSON => 'array|bool|int|float|string',
            Types::OBJECT => 'mixed',
            Types::SIMPLE_ARRAY => 'array',
            Types::SMALLINT => 'int',
            Types::STRING => 'string',
            Types::TEXT => 'string',
            Types::TIME_MUTABLE => '\\'.\DateTimeInterface::class,
            Types::TIME_IMMUTABLE => '\\'.\DateTimeImmutable::class,
        ];

        $this->dbalNameToPhpstanMapping = [
            Types::ARRAY => 'array<array-key, mixed>',
            Types::JSON => 'array<array-key, mixed>|bool|int|float|string',
            Types::SIMPLE_ARRAY => 'list<string>',
            Types::BINARY => 'resource',
            Types::BLOB => 'resource',
        ];
    }

    public function addDbTypeToDbalNameMapping(string $dbType, string $dbalName): self
    {
        $this->dbTypeToDbalNameMapping[$dbType] = $dbalName;

        return $this;
    }

    public function addDbalNameToPhpMapping(string $dbalName, string $php): self
    {
        $this->dbalNameToPhpMapping[$dbalName] = $php;

        return $this;
    }

    public function addDbalNameToPhpstanMapping(string $dbalName, string $phpstan): self
    {
        $this->dbalNameToPhpMapping[$dbalName] = $phpstan;

        return $this;
    }

    /** @param "mysql" $platform */
    public function dbTypeToDbalType(string $platform, string $dbType): Type
    {
        $mapping = [];
        if ('mysql' === $platform) {
            $mapping = array_merge($mapping, $this->mysqlDbTypeToDbalNameMapping);
        }

        $mapping = array_merge($mapping, $this->dbTypeToDbalNameMapping);
        $dbalName = $mapping[$dbType] ?? 'string';

        return Type::getType($dbalName);
    }

    public function dbalTypeToPhpType(Type $type): string
    {
        $dbalName = Type::getTypeRegistry()->lookupName($type);

        return $this->dbalNameToPhpMapping[$dbalName] ?? 'mixed';
    }

    public function dbalTypeToPhpstanType(Type $type): string
    {
        $dbalName = Type::getTypeRegistry()->lookupName($type);

        return $this->dbalNameToPhpstanMapping[$dbalName] ?? $this->dbalTypeToPhpType($type);
    }

    /**
     * @param int<min, max> $mysqliType
     */
    public function mysqliTypeToDbType(int $mysqliType): string
    {
        return match ($mysqliType) {
            \MYSQLI_TYPE_DECIMAL => 'decimal',
            \MYSQLI_TYPE_TINY => 'tinyint',
            \MYSQLI_TYPE_SHORT => 'smallint',
            \MYSQLI_TYPE_LONG => 'int',
            \MYSQLI_TYPE_FLOAT => 'float',
            \MYSQLI_TYPE_DOUBLE => 'double',
            \MYSQLI_TYPE_NULL => 'null',
            \MYSQLI_TYPE_TIMESTAMP => 'timestamp',
            \MYSQLI_TYPE_LONGLONG => 'bigint',
            \MYSQLI_TYPE_INT24 => 'mediumint',
            \MYSQLI_TYPE_DATE => 'date',
            \MYSQLI_TYPE_TIME => 'time',
            \MYSQLI_TYPE_DATETIME => 'datetime',
            \MYSQLI_TYPE_YEAR => 'year',
            \MYSQLI_TYPE_NEWDATE => 'date',
            \MYSQLI_TYPE_ENUM => 'enum',
            \MYSQLI_TYPE_SET => 'set',
            \MYSQLI_TYPE_TINY_BLOB => 'tinytext',
            \MYSQLI_TYPE_MEDIUM_BLOB => 'mediumtext',
            \MYSQLI_TYPE_LONG_BLOB => 'longtext',
            \MYSQLI_TYPE_BLOB => 'text',
            \MYSQLI_TYPE_VAR_STRING => 'varchar',
            \MYSQLI_TYPE_STRING => 'string',
            \MYSQLI_TYPE_CHAR => 'char', // @phpstan-ignore-line
            \MYSQLI_TYPE_INTERVAL => 'interval', // @phpstan-ignore-line
            \MYSQLI_TYPE_GEOMETRY => 'geometry',
            \MYSQLI_TYPE_NEWDECIMAL => 'numeric',

            default => throw new \RuntimeException(sprintf('Unknown type %s', $mysqliType)),
        };
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

use Doctrine\DBAL\Types\Type;

interface TypeMapperInterface
{
    /** @param "mysql" $platform */
    public function dbTypeToDbalType(string $platform, string $dbType): Type;

    public function dbalTypeToPhpType(Type $type): string;

    public function dbalTypeToPhpstanType(Type $type): string;

    public function mysqliTypeToDbType(int $mysqliType): string;
}

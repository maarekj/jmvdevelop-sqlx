<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx;

use Doctrine\DBAL\SQL\Parser;
use JmvDevelop\Sqlx\Exception\SqlxException;
use function Psl\Regex\every_match;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Symfony\Component\String\u;

final class ParameterParser
{
    public function __construct()
    {
    }

    /**
     * @return list<ParameterDescription>
     *
     * @throws \Doctrine\DBAL\SQL\Parser\Exception
     * @throws SqlxException
     * @throws \Doctrine\DBAL\Exception
     */
    public function extractParameters(string $sql): array
    {
        $matches = every_match(
            subject: $sql,
            pattern: '/^ *-- *@param *([-_a-zA-Z0-9|]+) *:([_a-zA-Z][_a-zA-Z0-9]*) *$/mi',
            capture_groups: shape([0 => string(), 1 => string(), 2 => string()]),
        );
        if (null === $matches) {
            return [];
        }

        $parameters = [];
        foreach ($matches as $match) {
            [1 => $type, 2 => $name] = $match;

            ['dbalTypeName' => $dbalTypeName, 'nullable' => $nullable] = $this->parseType($type);

            $parameters[] = new ParameterDescription(
                name: $name,
                dbalTypeName: $dbalTypeName,
                nullable: $nullable,
            );
        }

        $parser = new Parser(mySQLStringEscaping: true);
        $visitor = new class() implements Parser\Visitor {
            /** @param list<string> $parameters */
            public function __construct(
                public bool $hasPositionalParameter = false,
                public array $parameters = [],
            ) {
            }

            public function acceptPositionalParameter(string $sql): void
            {
                $this->hasPositionalParameter = true;
            }

            public function acceptNamedParameter(string $sql): void
            {
                $this->parameters[] = $sql;
            }

            public function acceptOther(string $sql): void
            {
            }
        };

        $parser->parse($sql, $visitor);

        if ($visitor->hasPositionalParameter) {
            throw new SqlxException(sprintf('Positional parameters are not supported : %s', $sql));
        }

        $nameOfParameters = array_map(fn (ParameterDescription $p) => $p->getName(), $parameters);
        $usedParameters = array_map(fn (string $p) => substr($p, 1), $visitor->parameters);

        foreach ($usedParameters as $p) {
            if (false === \in_array($p, $nameOfParameters, true)) {
                throw new SqlxException("The type of parameter '{$p}' is not defined");
            }
        }

        foreach ($nameOfParameters as $p) {
            if (false === \in_array($p, $usedParameters, true)) {
                throw new SqlxException("The parameter '{$p}' is not used in the query");
            }
        }

        return $parameters;
    }

    /**
     * @return array{dbalTypeName: string, nullable: bool}
     * @throws \Doctrine\DBAL\Exception|SqlxException
     */
    private function parseType(string $type): array
    {
        $types = explode('|', $type);
        if (1 === \count($types)) {
            $t = u($type)->lower()->toString();

            return ['dbalTypeName' => $t, 'nullable' => false];
        } elseif (2 === \count($types)) {
            [$t1, $t2] = $types;
            $t1 = u($t1)->lower()->toString();
            $t2 = u($t2)->lower()->toString();

            if ('null' === $t1) {
                return ['dbalTypeName' => $t2, 'nullable' => true];
            } elseif ('null' === $t2) {
                return ['dbalTypeName' => $t1, 'nullable' => true];
            }
        }

        throw new SqlxException("Invalid type '{$type}'");
    }
}

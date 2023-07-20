<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use JmvDevelop\Sqlx\Runtime\DefaultValue;
use JmvDevelop\Sqlx\TypeMapperInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use function JmvDevelop\Sqlx\Utils\addCreateMethodInClassFromConstructor;
use function JmvDevelop\Sqlx\Utils\assertNotNull;
use function Psl\Vec\sort_by;
use function Symfony\Component\String\u;

final class SchemaGenerator
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Filesystem $fs,
        private readonly string $namespace,
        private readonly TypeMapperInterface $typeMapper,
    ) {
    }

    /** @throws Exception|FilesystemException */
    public function generate(): void
    {
        $sm = $this->connection->createSchemaManager();
        foreach ($sm->listTables() as $table) {
            $this->generateTable($table);
        }
    }

    /** @throws FilesystemException */
    public function generateTable(Table $table): void
    {
        /** @var list<Column> $columns */
        $columns = array_values(sort_by($table->getColumns(), function (Column $c): bool {
            return null !== $c->getDefault() || $c->getAutoincrement();
        }));

        $createClass = function (string $suffixClass, string $typeClass, ClassType|null $criteriaClass) use ($table, $columns): ClassType {
            $forceHasDefault = 'update' === $typeClass || 'criteria' === $typeClass;
            $objFile = new PhpFile();
            $objFile->setStrictTypes();
            $ns = $objFile->addNamespace($this->namespace);
            $class = $ns->addClass(u($table->getName())->camel()->title().$suffixClass);
            $class->setFinal();

            $class->addConstant('TABLE_NAME', $table->getName())->setPublic();

            $construct = $this->addConstructMethod(class: $class, columns: $columns, forceHasDefault: $forceHasDefault);
            addCreateMethodInClassFromConstructor(class: $class, construct: $construct, createName: 'create');

            $this->addGetters(class: $class, columns: $columns, forceHasDefault: $forceHasDefault);
            $this->addWithers(class: $class, columns: $columns, forceHasDefault: $forceHasDefault);
            $this->addCloneMethod(class: $class, columns: $columns);
            $this->addToArrayMethod(class: $class, columns: $columns);
            $this->addDoctrineTypesMethod(class: $class, columns: $columns);

            if ('insert' === $typeClass) {
                $this->addInsertMethod(class: $class);
            } elseif ('update' === $typeClass) {
                assertNotNull($criteriaClass);
                $this->addUpdateMethod(class: $class, criteriaClass: $criteriaClass, ns: $ns);
            }

            $this->fs->write($class->getName().'.php', $objFile->__toString());

            return $class;
        };

        $criteriaClass = $createClass('Criteria', 'criteria', null);
        $objClass = $createClass('Object', 'insert', $criteriaClass);
        $partialClass = $createClass('Partial', 'update', $criteriaClass);
    }

    private function phpTypeForColumn(Column $column, bool $forceHasDefault): string
    {
        $isNotNull = $column->getNotnull();
        $isNullable = !$isNotNull;
        $hasDefault = $forceHasDefault || null !== $column->getDefault() || $column->getAutoincrement();

        $dbalType = $column->getType();
        $phpType = $this->typeMapper->dbalTypeToPhpType(type: $dbalType);

        return $phpType
            .($isNullable ? '|null' : '')
            .($hasDefault ? '|\\'.DefaultValue::class : '');
    }

    private function phpstanTypeForColumn(Column $column, bool $forceHasDefault): string
    {
        $isNotNull = $column->getNotnull();
        $isNullable = !$isNotNull;
        $hasDefault = $forceHasDefault || null !== $column->getDefault() || $column->getAutoincrement();

        $dbalType = $column->getType();
        $phpstanType = $this->typeMapper->dbalTypeToPhpstanType(type: $dbalType);

        return $phpstanType
            .($isNullable ? '|null' : '')
            .($hasDefault ? '|\\'.DefaultValue::class : '');
    }

    /** @param list<Column> $columns */
    private function addConstructMethod(ClassType $class, array $columns, bool $forceHasDefault): Method
    {
        $construct = $class->addMethod('__construct');

        foreach ($columns as $column) {
            $hasDefault = $forceHasDefault || null !== $column->getDefault() || $column->getAutoincrement();
            $phpType = $this->phpTypeForColumn(column: $column, forceHasDefault: $forceHasDefault);

            $param = $construct->addPromotedParameter($column->getName());
            $param->setType($phpType);
            $param->setPrivate();

            if ($hasDefault) {
                $param->setDefaultValue(new Literal('new \\'.DefaultValue::class.'()'));
            }
        }

        return $construct;
    }

    /** @param list<Column> $columns */
    private function addGetters(ClassType $class, array $columns, bool $forceHasDefault): void
    {
        foreach ($columns as $column) {
            $phpType = $this->phpTypeForColumn(column: $column, forceHasDefault: $forceHasDefault);

            $class
                ->addMethod('get'.u($column->getName())->camel()->title())
                ->setReturnType($phpType)
                ->setBody('return $this->'.$column->getName().';')
            ;
        }
    }

    /** @param list<Column> $columns */
    private function addWithers(ClassType $class, array $columns, bool $forceHasDefault): void
    {
        foreach ($columns as $column) {
            $body = "return new static(\n";
            foreach ($columns as $innerColumn) {
                if ($innerColumn->getName() === $column->getName()) {
                    $body .= '    '.$innerColumn->getName().': $'.$column->getName().",\n";
                } else {
                    $body .= '    '.$innerColumn->getName().': $this->'.$innerColumn->getName().",\n";
                }
            }
            $body .= ');';

            $method = $class->addMethod('with'.u($column->getName())->camel()->title());
            $method->setReturnType('static');
            $method->setBody($body);
            $method->addComment("@param {$this->phpstanTypeForColumn(column: $column, forceHasDefault: $forceHasDefault)} \${$column->getName()}");
            $method->addParameter($column->getName())->setType($this->phpTypeForColumn(column: $column, forceHasDefault: $forceHasDefault));
        }
    }

    /** @param list<Column> $columns */
    private function addCloneMethod(ClassType $class, array $columns): void
    {
        $method = $class->addMethod('clone');
        $method->setReturnType('static');

        $body = "return new static(\n";
        foreach ($columns as $c) {
            $body .= '    '.$c->getName().': $this->'.$c->getName().",\n";
        }
        $body .= ');';
        $method->setBody($body);
    }

    /** @param list<Column> $columns */
    private function addToArrayMethod(ClassType $class, array $columns): void
    {
        $dumper = new Dumper();

        $method = $class->addMethod('toArray');
        $method->setReturnType('array');

        $body = '$return = [];'."\n";
        foreach ($columns as $innerColumn) {
            $body .= 'if (false === ($this->'.$innerColumn->getName().' instanceof \\'.DefaultValue::class.')) {'."\n";
            $body .= '    $return['.$dumper->dump($innerColumn->getName()).'] = $this->'.$innerColumn->getName().";\n";
            $body .= "}\n";
        }
        $body .= 'return $return;';
        $method->setBody($body);
    }

    private function addInsertMethod(ClassType $class): void
    {
        $insert = $class->addMethod('insert');
        $insert->addParameter('connection')->setType(Connection::class);
        $insert->setReturnType('int');
        $insert->addComment('@throws \\Doctrine\\DBAL\\Exception');

        $insertBody = '$row = $this->toArray();'."\n";
        $insertBody .= '$types = $this->doctrineTypes();'."\n";
        $insertBody .= 'return $connection->insert(self::TABLE_NAME, $row, $types);'."\n";
        $insert->setBody($insertBody);

        $insertAndLastIntId = $class->addMethod('insertAndLastIntId');
        $insertAndLastIntId->addParameter('connection')->setType(Connection::class);
        $insertAndLastIntId->setReturnType('int');
        $insertAndLastIntId->addComment('@throws \\Doctrine\\DBAL\\Exception');
        $insertAndLastIntId->setBody('$this->insert(connection: $connection);'."\n");
        $insertAndLastIntId->addBody('return (int) $connection->lastInsertId();');

        $insertAndLastStringId = $class->addMethod('insertAndLastStringId');
        $insertAndLastStringId->addParameter('connection')->setType(Connection::class);
        $insertAndLastStringId->setReturnType('string');
        $insertAndLastStringId->addComment('@throws \\Doctrine\\DBAL\\Exception');
        $insertAndLastStringId->setBody('$this->insert(connection: $connection);'."\n");
        $insertAndLastStringId->addBody('return (string) $connection->lastInsertId();');
    }

    private function addUpdateMethod(ClassType $class, ClassType $criteriaClass, PhpNamespace $ns): void
    {
        $method = $class->addMethod('update');
        $method->addParameter('connection')->setType(Connection::class);
        $method->addParameter('criteria')->setType($this->resolveName($criteriaClass->getName()));
        $method->setReturnType('int');
        $method->addComment('@throws \\Doctrine\\DBAL\\Exception');

        $body = '$row = $this->toArray();'."\n";
        $body .= '$types = \array_merge([], $this->doctrineTypes(), $criteria->doctrineTypes());'."\n";
        $body .= 'return $connection->update(table: self::TABLE_NAME, data: $row, criteria: $criteria->toArray(), types: $types);'."\n";
        $method->setBody($body);
    }

    /** @param list<Column> $columns */
    private function addDoctrineTypesMethod(ClassType $class, array $columns): void
    {
        $dumper = new Dumper();
        $method = $class->addMethod('doctrineTypes');
        $method->addComment('@return array<string, \\'.Type::class.'>');
        $method->setReturnType('array');

        $body = '$types = [];'."\n";
        foreach ($columns as $innerColumn) {
            $body .= 'if (false === ($this->'.$innerColumn->getName().' instanceof \\'.DefaultValue::class.')) {'."\n";
            $body .= '    $types['.$dumper->dump($innerColumn->getName()).'] = '.$dumper->dump($innerColumn->getType()).";\n";
            $body .= "}\n";
        }
        $body .= 'return $types;'."\n";
        $method->setBody($body);
    }

    private function resolveName(?string $class): string
    {
        assertNotNull($class);
        $ns = new PhpNamespace($this->namespace);

        return $ns->resolveName($class);
    }
}

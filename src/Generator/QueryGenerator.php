<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use JmvDevelop\Sqlx\Describer;
use JmvDevelop\Sqlx\Exception\SqlxException;
use JmvDevelop\Sqlx\ParameterDescription;
use JmvDevelop\Sqlx\ParameterParser;
use JmvDevelop\Sqlx\QueryDescription;
use JmvDevelop\Sqlx\QueryFinder\Source;
use JmvDevelop\Sqlx\Runtime\Result;
use JmvDevelop\Sqlx\TypeMapperInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use function JmvDevelop\Sqlx\Utils\addCreateMethodInClassFromConstructor;
use function JmvDevelop\Sqlx\Utils\assertNotNull;
use function JmvDevelop\Sqlx\Utils\stripCommentsInSql;
use function Symfony\Component\String\u;

final readonly class QueryGenerator
{
    public function __construct(
        private Describer $describer,
        private Filesystem $fs,
        private string $namespace,
        private TypeMapperInterface $typeMapper,
        private ParameterParser $parameterParser = new ParameterParser(),
    ) {
    }

    /**
     * @throws Exception|FilesystemException|SqlxException
     */
    public function generate(Source $source): void
    {
        $description = $this->describer->describeQuery($source->getContent());
        $parameters = $this->parameterParser->extractParameters($source->getContent());

        [$queryClass, $queryFile, $queryNs] = $this->createClass(className: u($source->getName())->camel()->title().'Query');
        [$rowClass, $rowFile, $rowNs] = $this->createClass(className: u($source->getName())->camel()->title().'Row');

        [$paramsClass, $paramsFile, $paramsNs] = [null, null, null];
        if (\count($parameters) >= 1) {
            [$paramsClass, $paramsFile, $paramsNs] = $this->createClass(className: u($source->getName())->camel()->title().'Params');
        }

        $this->prepareQueryClass(source: $source, description: $description, paramsClass: $paramsClass, queryClass: $queryClass, rowClass: $rowClass, parameters: $parameters);
        $this->prepareRowClass(source: $source, description: $description, rowClass: $rowClass, queryClass: $queryClass);

        if (null !== $paramsClass) {
            $this->prepareParamsClass(source: $source, paramsClass: $paramsClass, parameters: $parameters);
        }

        $this->fs->write($queryClass->getName().'.php', $queryFile->__toString());
        $this->fs->write($rowClass->getName().'.php', $rowFile->__toString());
        if (null !== $paramsClass && null !== $paramsFile) {
            $this->fs->write($paramsClass->getName().'.php', $paramsFile->__toString());
        }
    }

    /**
     * @param list<ParameterDescription> $parameters
     */
    private function prepareQueryClass(Source $source, QueryDescription $description, ClassType $queryClass, ?ClassType $paramsClass, ClassType $rowClass, array $parameters): void
    {
        $dumper = new Dumper();

        $queryClass->setFinal();
        $queryClass->addConstant('content', $source->getContent());
        $queryClass->addConstant('sql', stripCommentsInSql($source->getContent()));

        $construct = $queryClass->addMethod('__construct');
        $construct->addPromotedParameter('connection')
            ->setReadOnly()
            ->setType('\\'.Connection::class)
            ->setPrivate()
        ;

        if (null !== $paramsClass) {
            $construct->addPromotedParameter('params')
                ->setReadOnly()
                ->setType($this->resolveName($paramsClass->getName()))
                ->setPrivate()
            ;
        }

        addCreateMethodInClassFromConstructor(class: $queryClass, construct: $construct, createName: 'create');

        $queryClass->addMethod('describe')
            ->setReturnType('\\'.QueryDescription::class)
            ->setBody('return '.$dumper->dump($description).';')
        ;

        $execute = $queryClass->addMethod('execute')->setReturnType('\\'.Result::class);
        $execute->addComment('@return \\'.Result::class.'<array-key, \\'.$this->resolveName($rowClass->getName()).'>');
        $execute->addComment('');
        $execute->addComment('@throws \\'.\Doctrine\DBAL\Exception::class);

        $execute->addBody('$stmt = $this->connection->prepare(self::sql);');
        $execute->addBody('');
        foreach ($parameters as $parameter) {
            $execute->addBody('$stmt->bindValue('
                .'param: '.$dumper->dump($parameter->getName()).', '
                .'value: $this->params->'.$parameter->getName().', '
                .'type: '.$dumper->dump($parameter->getDbalTypeName())
                .');');
        }

        $execute->addBody('');
        $execute->addBody('return new \\'.Result::class.'(');
        $execute->addBody('    result: $stmt->executeQuery(),');
        $execute->addBody('    parseRow: fn(array $row) => $this->parseRow(row: $row, connection: $this->connection),');
        $execute->addBody(');');
    }

    private function prepareRowClass(Source $source, QueryDescription $description, ClassType $rowClass, ClassType $queryClass): void
    {
        $rowClass->setFinal();
        $rowClass->setReadOnly();

        $construct = $rowClass->addMethod('__construct');

        foreach ($description->getColumns() as $column) {
            $name = $column->getName();
            $dbalType = $this->typeMapper->dbTypeToDbalType(platform: 'mysql', dbType: $column->getDbType());
            $phpType = $this->typeMapper->dbalTypeToPhpType($dbalType).($column->isNullable() ? '|null' : '');
            $phpstanType = '('.$this->typeMapper->dbalTypeToPhpstanType($dbalType).')'.($column->isNullable() ? '|null' : '');

            $construct
                ->addPromotedParameter(name: $name)
                ->setType($phpType)
                ->setPublic()
                ->addComment('@param '.$phpstanType.' $'.$name)
            ;
        }

        $this->addMethodParseRow(queryClass: $queryClass, rowClass: $rowClass, description: $description);

        addCreateMethodInClassFromConstructor(class: $rowClass, construct: $construct, createName: 'create');
    }

    private function addMethodParseRow(ClassType $queryClass, ClassType $rowClass, QueryDescription $description): void
    {
        $dumper = new Dumper();

        $method = $queryClass->addMethod('parseRow');
        $method->setReturnType($this->resolveName($rowClass->getName()));
        $method->setStatic();

        $method->addComment('@param array<string, mixed> $row');
        $rowParam = $method->addParameter('row');
        $rowParam->setType('array');

        $platformParam = $method->addParameter('connection');
        $platformParam->setType(Connection::class);

        $body = 'return new '.$rowClass->getName()."(\n";
        foreach ($description->getColumns() as $column) {
            $name = $column->getName();
            $dbalType = $this->typeMapper->dbTypeToDbalType('mysql', $column->getDbType());
            $dbalName = Type::getTypeRegistry()->lookupName($dbalType);
            $body .= '    '.$name.': \\'.Type::class.'::getType('.$dumper->dump($dbalName).')->convertToPHPValue(value: $row['.$dumper->dump($name).'], platform: $connection->getDatabasePlatform()),'."\n";
        }
        $body .= ');';
        $method->setBody($body);
    }

    /**
     * @param  list<ParameterDescription> $parameters
     * @throws Exception
     */
    private function prepareParamsClass(Source $source, array $parameters, ClassType $paramsClass): void
    {
        $paramsClass->setFinal();
        $paramsClass->setReadOnly();

        $construct = $paramsClass->addMethod('__construct');

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $dbalType = Type::getType($parameter->getDbalTypeName());
            $phpType = $this->typeMapper->dbalTypeToPhpType($dbalType).($parameter->isNullable() ? '|null' : '');
            $phpstanType = '('.$this->typeMapper->dbalTypeToPhpstanType($dbalType).')'.($parameter->isNullable() ? '|null' : '');

            $construct
                ->addPromotedParameter(name: $name)
                ->setType($phpType)
                ->setPublic()
                ->addComment('@param '.$phpstanType.' $'.$name)
            ;

            $with = $paramsClass->addMethod('with'.u($name)->camel()->title());
            $with->setReturnType('self');
            $with->addComment('@param '.$phpstanType.' $'.$name);
            $with->addParameter($name)->setType($phpType);

            $with->addBody('return new self(');
            foreach ($parameters as $innerParameter) {
                $innerName = $innerParameter->getName();
                if ($innerName === $name) {
                    $with->addBody("    {$innerName}: \${$innerName},");
                } else {
                    $with->addBody("    {$innerName}: \$this->{$innerName},");
                }
            }
            $with->addBody(');');
        }

        addCreateMethodInClassFromConstructor(class: $paramsClass, construct: $construct, createName: 'create');
    }

    /** @return array{0: ClassType, 1: PhpFile, 2: PhpNamespace} */
    private function createClass(string $className): array
    {
        $file = new PhpFile();
        $file->setStrictTypes();
        $ns = $file->addNamespace($this->namespace);
        $class = $ns->addClass($className);

        return [$class, $file, $ns];
    }

    private function resolveName(?string $class): string
    {
        assertNotNull($class);
        $ns = new PhpNamespace($this->namespace);

        return $ns->resolveName($class);
    }
}

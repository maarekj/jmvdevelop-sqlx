<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Utils;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PromotedParameter;
use function Psl\Type\nullable;
use function Psl\Type\string;

/**
 * @template T
 * @param  null|T $value
 * @return T
 *
 * @phpstan-assert T $value
 * @phpstan-assert !null $value
 */
function assertNotNull(mixed $value)
{
    if (null === $value) {
        throw new \RuntimeException('Must be not null');
    }

    return $value;
}

function strDef(string|null $value, string $default): string
{
    return null === $value ? $default : $value;
}

function addCreateMethodInClassFromConstructor(ClassType $class, Method $construct, string $createName): Method
{
    $create = $class->addMethod($createName)->setStatic();
    $create->setReturnType('self');

    foreach ($construct->getParameters() as $parameter) {
        $p = $create->addParameter($parameter->getName());
        $p->setType(nullable(string())->coerce($parameter->getType()));
        $p->setNullable($parameter->isNullable());

        if ($parameter->hasDefaultValue()) {
            $p->setDefaultValue($parameter->getDefaultValue());
        }
    }

    $create->addBody('return new self(');
    foreach ($construct->getParameters() as $parameter) {
        $create->addBody('    '.$parameter->getName().': $'.$parameter->getName().',');
    }
    $create->addBody(');');

    if (null !== $construct->getComment()) {
        $create->addComment($construct->getComment());
    }
    foreach ($construct->getParameters() as $parameter) {
        if ($parameter instanceof PromotedParameter && ($comment = $parameter->getComment()) !== null) {
            $create->addComment($comment);
        }
    }

    return $create;
}

function stripCommentsInSql(string $sql): string
{
    return \Psl\Regex\replace(haystack: $sql, pattern: '/^ *--.*$/m', replacement: '');
}

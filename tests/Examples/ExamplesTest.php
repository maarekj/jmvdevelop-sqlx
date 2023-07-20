<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests\Examples;

use Doctrine\DBAL\Connection;
use JmvDevelop\Sqlx\Examples\Generated\Query\FindUserByIdParams;
use JmvDevelop\Sqlx\Examples\Generated\Query\FindUserByIdQuery;
use JmvDevelop\Sqlx\Examples\Generated\Query\FindUserByIdRow;
use JmvDevelop\Sqlx\Examples\Generated\Query\GetAllUsersQuery;
use JmvDevelop\Sqlx\Examples\Generated\Query\GetAllUsersRow;
use JmvDevelop\Sqlx\Examples\Generated\Schema\UserCriteria;
use JmvDevelop\Sqlx\Examples\Generated\Schema\UserObject;
use JmvDevelop\Sqlx\Examples\Generated\Schema\UserPartial;
use JmvDevelop\Sqlx\Runtime\DefaultValue;
use JmvDevelop\Sqlx\Runtime\Result;
use JmvDevelop\Sqlx\Tests\WithDatabaseManagerTrait;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/** @internal */
#[CoversClass(GetAllUsersQuery::class)]
#[UsesClass(DefaultValue::class)]
#[UsesClass(Result::class)]
final class ExamplesTest extends TestCase
{
    use WithDatabaseManagerTrait;

    #[BeforeClass]
    public function beforeClass(): void
    {
    }

    public function testExamples(): void
    {
        self::getDbManager()->installSchema();

        $this->autoRollbackTransaction(function (Connection $dbal): void {
            // Insert users
            //

            $josephId = UserObject::create(
                id: 1,
                name: 'Joseph Maarek',
                password: '12345',
                email: 'joseph@test.com',
                date_inscription: new \DateTimeImmutable('2023-07-18'),
            )->insertAndLastIntId($dbal);
            $this->assertSame(1, $josephId);

            $vanessaId = UserObject::create(
                id: 2,
                name: 'Vanessa Maarek',
                password: '12345',
                email: 'vanessa@test.com',
                date_inscription: new \DateTimeImmutable('2023-07-18'),
            )->insertAndLastIntId($dbal);
            $this->assertSame(2, $vanessaId);

            $result = GetAllUsersQuery::create(connection: $dbal)->execute();
            $this->assertSame(2, $result->rowCount());
            $this->assertEquals([
                GetAllUsersRow::create(id: 1, name: 'Joseph Maarek', password: '12345', email: 'joseph@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
                GetAllUsersRow::create(id: 2, name: 'Vanessa Maarek', password: '12345', email: 'vanessa@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
            ], $result->toArray());

            // Update user
            //
            $this->assertSame(1, (new UserPartial(
                name: 'Joseph Maarek updated'
            ))->update(connection: $dbal, criteria: new UserCriteria(id: $josephId)));

            $this->assertSame(1, (new UserPartial(
                name: 'Vanessa Maarek updated'
            ))->update(connection: $dbal, criteria: new UserCriteria(id: $vanessaId)));

            $result = GetAllUsersQuery::create(connection: $dbal)->execute();
            $this->assertSame(2, $result->rowCount());
            $this->assertEquals([
                GetAllUsersRow::create(id: 1, name: 'Joseph Maarek updated', password: '12345', email: 'joseph@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
                GetAllUsersRow::create(id: 2, name: 'Vanessa Maarek updated', password: '12345', email: 'vanessa@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
            ], $result->toArray());

            // Find user by id
            //
            $this->assertEquals(
                FindUserByIdRow::create(id: 1, name: 'Joseph Maarek updated', password: '12345', email: 'joseph@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
                FindUserByIdQuery::create(
                    connection: $dbal,
                    params: FindUserByIdParams::create(id: 1)
                )->execute()->firstOrThrow(),
            );

            $this->assertEquals(
                FindUserByIdRow::create(id: 2, name: 'Vanessa Maarek updated', password: '12345', email: 'vanessa@test.com', date_inscription: new \DateTimeImmutable('2023-07-18')),
                FindUserByIdQuery::create(
                    connection: $dbal,
                    params: FindUserByIdParams::create(id: 2)
                )->execute()->firstOrThrow(),
            );
        });
    }
}

<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\BeforeClass;

trait WithDatabaseManagerTrait
{
    private static DatabaseManager|null $withDatabaseManager_dbManager = null;

    #[BeforeClass]
    public static function withDatabaseManager_beforeClass(): void
    {
        self::$withDatabaseManager_dbManager = new DatabaseManager();
        self::$withDatabaseManager_dbManager->installSchema();
    }

    public static function getDbManager(): DatabaseManager
    {
        return self::$withDatabaseManager_dbManager ?? throw new \RuntimeException('DatabaseManager not initialized');
    }

    #[AfterClass]
    public function withDatabaseManager_afterClass(): void
    {
        self::$withDatabaseManager_dbManager = null;
    }

    /**
     * @template T
     * @param  callable(Connection):void $callable
     * @return T
     * @throws \Doctrine\DBAL\Exception
     */
    public function autoRollbackTransaction(callable $callable): mixed
    {
        try {
            self::getDbManager()->getMysqlDbal()->beginTransaction();

            return $callable(self::getDbManager()->getMysqlDbal());
        } finally {
            self::getDbManager()->getMysqlDbal()->rollBack();
        }
    }
}

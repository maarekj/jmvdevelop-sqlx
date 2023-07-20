<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;

final class DatabaseManager
{
    private Connection|null $mysqlDbal = null;

    public function __construct()
    {
    }

    /**
     * @return array{hostname: string, username: string, password: string, database: string, port: int}
     */
    public static function mysqliDatabaseConfig(): array
    {
        return [
            'hostname' => '127.0.0.1',
            'username' => 'root',
            'password' => 'password',
            'database' => 'sqlx',
            'port' => 3306,
        ];
    }

    public function getMysqlDbal(): Connection
    {
        if (null === $this->mysqlDbal) {
            $config = self::mysqliDatabaseConfig();
            $this->mysqlDbal = DriverManager::getConnection([
                'dbname' => $config['database'],
                'user' => $config['username'],
                'password' => $config['password'],
                'host' => $config['hostname'],
                'port' => $config['port'],
                'driver' => 'pdo_mysql',
            ]);
        }

        return $this->mysqlDbal;
    }

    public function dropSchema(): void
    {
        $sm = $this->getMysqlDbal()->createSchemaManager();
        $platform = $this->getMysqlDbal()->getDatabasePlatform();
        $comparator = $sm->createComparator();

        $schemaDiff = $comparator->compareSchemas(fromSchema: $sm->introspectSchema(), toSchema: new Schema());
        foreach ($platform->getAlterSchemaSQL(diff: $schemaDiff) as $query) {
            $this->getMysqlDbal()->executeQuery($query);
        }
    }

    /** @throws Exception */
    public function installSchema(): void
    {
        $platform = $this->getMysqlDbal()->getDatabasePlatform();

        $schema = new Schema();
        $t_category = $schema->createTable('category');
        $t_category->addColumn('id', 'integer')->setAutoincrement(true);
        $t_category->addColumn('name', 'string', ['length' => 50])->setNotnull(true);
        $t_category->setPrimaryKey(['id']);

        $t_user = $schema->createTable('`user`');
        $t_user->addColumn('id', 'integer')->setAutoincrement(true);
        $t_user->addColumn('name', 'string', ['length' => 50])->setNotnull(true);
        $t_user->addColumn('password', 'string', ['length' => 100])->setNotnull(true);
        $t_user->addColumn('email', 'string', ['length' => 100])->setNotnull(true);
        $t_user->addColumn('date_inscription', 'date')->setNotnull(true);
        $t_user->setPrimaryKey(['id']);
        $t_user->addUniqueConstraint(['email']);

        $t_post = $schema->createTable('post');
        $t_post->addColumn('id', 'integer')->setAutoincrement(1);
        $t_post->addColumn('title', 'string', ['length' => 100])->setNotnull(true)->setDefault('title of post');
        $t_post->addColumn('content', 'text')->setNotnull(true);
        $t_post->addColumn('date_creation', 'date')->setNotnull(true);
        $t_post->addColumn('h1', 'string', ['length' => 100])->setNotnull(false)->setDefault(null);
        $t_post->addColumn('author_id', 'integer')->setNotnull(true);
        $t_post->addColumn('category_id', 'integer');
        $t_post->setPrimaryKey(['id']);
        $t_post->addForeignKeyConstraint(foreignTable: $t_category, foreignColumnNames: ['id'], localColumnNames: ['category_id']);
        $t_post->addForeignKeyConstraint(foreignTable: $t_user, foreignColumnNames: ['id'], localColumnNames: ['author_id']);

        $t_comment = $schema->createTable('comment');
        $t_comment->addColumn('id', 'integer')->setAutoincrement(1);
        $t_comment->addColumn('content', 'text')->setNotnull(true);
        $t_comment->addColumn('date_creation', 'date')->setNotnull(true);
        $t_comment->addColumn('author_id', 'integer')->setNotnull(true);
        $t_comment->addColumn('post_id', 'integer')->setNotnull(true);
        $t_comment->setPrimaryKey(['id']);
        $t_comment->addForeignKeyConstraint(foreignTable: $t_post, foreignColumnNames: ['id'], localColumnNames: ['post_id']);
        $t_comment->addForeignKeyConstraint(foreignTable: $t_user, foreignColumnNames: ['id'], localColumnNames: ['author_id']);

        $this->dropSchema();

        foreach ($schema->toSql($platform) as $query) {
            $this->getMysqlDbal()->executeQuery($query);
        }
    }
}

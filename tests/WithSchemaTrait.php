<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Tests;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;

trait WithSchemaTrait
{
    #[Before]
    public function withSchema_before(): void
    {
        self::getDbManager()->installSchema();
    }

    #[After]
    public function withSchema_after(): void
    {
        self::getDbManager()->dropSchema();
    }
}

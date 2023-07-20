<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Exception;

class PrepareException extends SqlxException
{
    public function __construct(private readonly string $sql, private readonly string|null $databaseError = null, \Throwable|null $previous = null)
    {
        $message = sprintf("Error (%s) when prepare sql:\n%s", $databaseError, $this->sql);
        parent::__construct(message: $message, previous: $previous);
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getDatabaseError(): ?string
    {
        return $this->databaseError;
    }
}

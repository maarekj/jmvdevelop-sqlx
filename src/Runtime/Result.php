<?php

declare(strict_types=1);

namespace JmvDevelop\Sqlx\Runtime;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result as DbalResult;
use JmvDevelop\Sqlx\Exception\SqlxException;
use Psl\Collection\Vector;
use Psl\Collection\VectorInterface;

/**
 * @template TKey of array-key
 * @template T
 */
final class Result
{
    private bool $alreadyFetched = false;

    /**
     * @param \Closure(array<string, mixed>):T $parseRow
     */
    public function __construct(
        private readonly DbalResult $result,
        private readonly \Closure $parseRow,
    ) {
    }

    /** @throws Exception */
    public function rowCount(): int
    {
        return $this->result->rowCount();
    }

    /** @throws Exception */
    public function columnCount(): int
    {
        return $this->result->columnCount();
    }

    /**
     * @return iterable<TKey, T>
     * @throws Exception|SqlxException
     */
    public function iterate(): iterable
    {
        $this->checkAlreadyFetchedAndMarkFetchedOrThrow();
        foreach ($this->result->iterateAssociative() as $key => $value) {
            yield $key => ($this->parseRow)($value);
        }
    }

    /**
     * @return array<TKey, T>
     * @throws Exception|SqlxException
     */
    public function toArray(): array
    {
        $this->checkAlreadyFetchedAndMarkFetchedOrThrow();
        $array = $this->result->fetchAllAssociative();

        return array_map($this->parseRow, $array);
    }

    /**
     * @return null|T
     * @throws Exception|SqlxException
     */
    public function firstOrNull(): mixed
    {
        $count = $this->rowCount();
        if ($count > 1) {
            throw new SqlxException('Result has more than one row.');
        }

        return $this->toPslVector()->first();
    }

    /**
     * @return T
     * @throws Exception|SqlxException
     */
    public function firstOrThrow(): mixed
    {
        $value = $this->firstOrNull();
        if (null === $value) {
            throw new SqlxException('Result has no row.');
        }

        return $value;
    }

    /**
     * @return Collection<TKey, T>
     * @throws Exception|SqlxException
     */
    public function toDoctrineCollection(): Collection
    {
        $array = $this->toArray();

        return new ArrayCollection($array);
    }

    /**
     * @return VectorInterface<T>
     * @throws Exception|SqlxException
     */
    public function toPslVector(): VectorInterface
    {
        $array = $this->toArray();

        return new Vector($array);
    }

    /** @throws SqlxException */
    private function checkAlreadyFetchedAndMarkFetchedOrThrow(): void
    {
        if (true === $this->alreadyFetched) {
            throw new SqlxException('Result already fetched. The result can be fetched only once.');
        }
        $this->alreadyFetched = true;
    }
}

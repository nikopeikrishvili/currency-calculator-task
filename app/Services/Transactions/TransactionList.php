<?php

namespace App\Services\Transactions;

use App\Services\Transaction\TransactionInterface;
use Iterator;

/**
 * @implements Iterator<TransactionInterface>
 */
class TransactionList implements TransactionListInterface, Iterator
{
    /**
     * @var array<TransactionInterface>
     */
    private array $list = [];
    private int $position = 0;
    public function __construct()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->list[$this->position];
    }

    public function next(): int
    {
        return ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
    /**
     * @inheritDoc
     */
    public function add(TransactionInterface $transaction): void
    {
        $this->list[] = $transaction;
    }

    /**
     * @inheritDoc
     */
    public function addFromCsvLine(array $csvLine): void
    {
    }
}

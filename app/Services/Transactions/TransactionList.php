<?php

namespace App\Services\Transactions;

use App\Services\Transaction\TransactionInterface;
use Exception;
use Iterator;

/**
 * @implements Iterator<TransactionInterface>
 */
final class TransactionList implements TransactionListInterface, Iterator
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

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }

    public function rewind(): void
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
        $transactionType = $csvLine['3'];
        $className = '\App\Services\Transaction\\' . ucfirst($transactionType);
        if (!class_exists($className)) {
            throw new Exception("Invalid transaction type");
        }
        $transaction = new $className(
            $csvLine['0'],
            $csvLine['1'],
            $csvLine['2'],
            $csvLine['3'],
            $csvLine['4'],
            $csvLine['5']
        );
        $this->list[] = $transaction;
    }
}

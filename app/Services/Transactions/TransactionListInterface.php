<?php

namespace App\Services\Transactions;

use App\Services\Transaction\TransactionInterface;

interface TransactionListInterface
{
    /**
     * Adds transaction to transaction list
     *
     * @param TransactionInterface $transaction
     * @return void
     */
    public function add(TransactionInterface $transaction): void;

    /**
     * Adds transaction to transaction list from CSV line array data
     *
     * @param array<int, mixed> $csvLine
     * @return void
     */
    public function addFromCsvLine(array $csvLine): void;
}

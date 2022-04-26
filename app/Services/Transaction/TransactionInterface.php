<?php

namespace App\Services\Transaction;

interface TransactionInterface
{
    /**
     * @return string
     */
    public function getDate(): string;

    /**
     * @param string $date
     * @return void
     */
    public function setDate(string $date): void;

    /**
     * @return int
     */
    public function getClientId(): int;

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId): void;

    /**
     * @return string
     */
    public function getClientType(): string;

    /**
     * @param string $clientType
     */
    public function setClientType(string $clientType): void;

    /**
     * @return string
     */
    public function getTransactionType(): string;

    /**
     * @param string $transactionType
     */
    public function setTransactionType(string $transactionType): void;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void;

    /**
     * @return float
     */
    public function getCommission(): float;

    /**
     * @param float $commission
     */
    public function setCommission(float $commission): void;
}

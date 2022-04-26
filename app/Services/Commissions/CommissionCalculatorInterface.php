<?php

namespace App\Services\Commissions;

use App\Services\Currency\CurrencyConfiguratorInterface;
use App\Services\Rates\RatesInterface;
use App\Services\Transactions\TransactionListInterface;

interface CommissionCalculatorInterface
{
    public function __construct(RatesInterface $ratesService, CurrencyConfiguratorInterface $currencyConfigurator);

    public function calculate(
        TransactionListInterface $transactionList
    ): TransactionListInterface;
}

<?php

namespace App\Services\Currency;

use Exception;

interface CurrencyConfiguratorInterface
{
    /**
     * Get scale for supported currency, if currency is not supported than throws an error
     *
     * @param string $currency
     * @return int
     * @throws Exception
     */
    public function getScaleForCurrency(string $currency): int;
}

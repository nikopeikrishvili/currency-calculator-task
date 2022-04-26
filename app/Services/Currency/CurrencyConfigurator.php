<?php

namespace App\Services\Currency;

use Exception;

class CurrencyConfigurator implements CurrencyConfiguratorInterface
{
    /**
     * @var array|int[]
     */
    private array $currencyConfiguration = [
        'USD' => 2,
        'EUR' => 2,
        'JPY' => 0,
    ];

    /**
     * @inheritDoc
     */
    public function getScaleForCurrency(string $currency): int
    {
        if (!key_exists($currency, $this->currencyConfiguration)) {
            throw new Exception('Unsupported currency ' . $currency);
        }
        return $this->currencyConfiguration[$currency];
    }
}

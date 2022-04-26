<?php

namespace App\Services\Rates;

use Exception;
use Illuminate\Support\Facades\Http;
use Throwable;

final class Rates implements RatesInterface
{
    /**
     * @var array<string,float>
     */
    private array $rates = [];

    /**
     * Get rates from API and saves to $rates array
     * @return void
     * @throws Exception
     */
    private function pullRates(): void
    {
        if (empty($this->rates)) {
            try {
                $response = Http::get('https://developers.paysera.com/tasks/api/currency-exchange-rates')->object();
                $this->rates = (array)$response->rates;
                $this->rates['USD'] = 1.1497;
                $this->rates['JPY'] = 129.53;
            } catch (Throwable $ex) {
                throw new Exception("Unable to fetch rates from rates service");
            }
        }
    }

    /**
     * Returns rate for the currency, if rate for the currency not found, throws exception
     * @param string $currency
     * @return float
     * @throws Exception
     */
    public function getRate(string $currency): float
    {
        $this->pullRates();
        if (!key_exists($currency, $this->rates)) {
            throw new Exception("Rate for " . $currency . " was not found");
        }
        return $this->rates[$currency];
    }
}

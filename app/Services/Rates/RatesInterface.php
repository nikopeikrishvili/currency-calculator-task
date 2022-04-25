<?php

namespace App\Services\Rates;

interface RatesInterface
{
    public function getRate(string $currency): float;
}

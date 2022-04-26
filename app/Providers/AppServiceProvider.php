<?php

namespace App\Providers;

use App\Services\Commissions\CommissionCalculator;
use App\Services\Commissions\CommissionCalculatorInterface;
use App\Services\Currency\CurrencyConfigurator;
use App\Services\Currency\CurrencyConfiguratorInterface;
use App\Services\Rates\Rates;
use App\Services\Rates\RatesInterface;
use App\Services\Transactions\TransactionList;
use App\Services\Transactions\TransactionListInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $bindings = [
        TransactionListInterface::class => TransactionList::class,
        CommissionCalculatorInterface::class => CommissionCalculator::class,
    ];

    /**
     * @var array|string[]
     */
    public array $singletons = [
        RatesInterface::class => Rates::class,
        CurrencyConfiguratorInterface::class => CurrencyConfigurator::class,

    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

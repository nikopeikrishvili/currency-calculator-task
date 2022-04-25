<?php

namespace App\Providers;

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

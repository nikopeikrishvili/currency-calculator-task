<?php

namespace App\Console\Commands;

use App\Services\Commissions\CommissionCalculatorInterface;
use App\Services\Currency\CurrencyConfiguratorInterface;
use App\Services\Transactions\TransactionList;
use App\Services\Transactions\TransactionListInterface;
use Illuminate\Console\Command;

class CalculateCommissionsCommand extends Command
{
    protected $signature = 'calculate:commission {file}';

    protected $description = 'Calculate Commissions from input CSV file';

    /**
     * @param TransactionList $transactionList
     * @param CommissionCalculatorInterface $calculator
     * @param CurrencyConfiguratorInterface $currencyConfigurator
     * @return int
     */
    public function handle(
        TransactionListInterface $transactionList,
        CommissionCalculatorInterface $calculator,
        CurrencyConfiguratorInterface $currencyConfigurator
    ): int {
        if (!$this->validateArgument()) {
            return -1;
        }
        try {
            $file = $this->argument('file');
            if (($open = fopen($file, "r")) !== false) {
                while (($data = fgetcsv($open, 1000, ",")) !== false) {
                    $transactionList->addFromCsvLine($data);
                }
                fclose($open);
            }

            $calculator->calculate($transactionList);
            foreach ($transactionList as $transaction) {
                $scale = $currencyConfigurator->getScaleForCurrency($transaction->getCurrency());
                $this->line(sprintf('%.' . $scale . 'f', $transaction->getCommission()));
            }
        } catch (\Throwable $exception) {
            $this->error('Exception ' . $exception->getMessage());
            if (isset($open) && is_resource($open)) {
                fclose($open);
            }
        }

        return 0;
    }

    private function validateArgument(): bool
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File ' . $file . ' not found ' . __DIR__);
            return false;
        }
        return true;
    }
}

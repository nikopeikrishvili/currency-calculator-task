<?php

namespace App\Console\Commands;

use App\Services\Transactions\TransactionListInterface;
use Illuminate\Console\Command;

class CalculateCommissionsCommand extends Command
{
    protected $signature = 'calculate:commission {file}';

    protected $description = 'Calculate Commissions from input CSV file';

    public function handle(TransactionListInterface $transactionList): int
    {
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
            dump($transactionList);
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
            $this->error('File ' . $file . ' not found');
            return false;
        }
        return true;
    }
}

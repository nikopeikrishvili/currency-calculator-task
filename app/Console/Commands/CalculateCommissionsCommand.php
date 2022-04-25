<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateCommissionsCommand extends Command
{
    protected $signature = 'calculate:commission {file}';

    protected $description = 'Calculate Commissions from input CSV file';

    public function handle(): int
    {
        if (!$this->validateArgument()) {
            return -1;
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

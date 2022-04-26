<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommissionCalculationOutputTest extends TestCase
{
    public function testOutput(): void
    {
        Artisan::call('calculate:commission', ['file' => base_path('tests/assets/input.csv')]);
        $result = Artisan::output();
        $this->assertEquals(
            '0.60
3.00
0.00
0.06
1.50
0
0.70
0.30
0.30
3.00
0.00
0.00
8612
',
            $result
        );
    }
}

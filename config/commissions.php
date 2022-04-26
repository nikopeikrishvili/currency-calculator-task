<?php

return [
    'deposit' => env('COMMON_COMMISSION_PERCENT_DEPOSIT', 0.03),
    'withdraw_private' => env('COMMON_COMMISSION_PERCENT_WITHDRAW_PRIVATE', 0.3),
    'withdraw_business' => env('COMMON_COMMISSION_PERCENT_WITHDRAW_BUSINESS', 0.5),
    'week_free_of_charge_tr_count' => env('FREE_OF_CHARGE_TR_COUNT_WEEK', 3),
    'week_free_of_charge_amount_sum' => env('FREE_OF_CHARGE_AMOUNT_SUM_WEEK', 1000),
];

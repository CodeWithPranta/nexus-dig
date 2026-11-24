<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | USD is the platform default. Amounts are stored in the currency recorded
    | on each record and converted for display using the exchange rates below.
    |
    */

    'default' => env('DEFAULT_CURRENCY', 'USD'),

    'base' => 'USD',

    'supported' => [
        'BDT' => [
            'name' => 'Bangladeshi Taka',
            'symbol' => '৳',
            'decimals' => 0,
            'locale' => 'bn_BD',
        ],
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'decimals' => 2,
            'locale' => 'en_US',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates (base: USD)
    |--------------------------------------------------------------------------
    */

    'exchange_rates' => [
        'USD' => 1.0,
        'BDT' => (float) env('BDT_PER_USD', 120),
    ],

];

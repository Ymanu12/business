<?php

return [
    'default_commission' => env('AFRITASK_COMMISSION', 0.20),
    'min_withdrawal'     => env('AFRITASK_MIN_WITHDRAWAL', 5000),
    'currency'           => env('AFRITASK_CURRENCY', 'XOF'),
    'auto_complete_days' => env('AFRITASK_AUTO_COMPLETE_DAYS', 3),

    'payments' => [
        'stripe'       => env('STRIPE_KEY'),
        'cinetpay_key' => env('CINETPAY_API_KEY'),
        'cinetpay_site'=> env('CINETPAY_SITE_ID'),
    ],
];

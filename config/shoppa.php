<?php

return [
    'name'    => 'Shoppa',
    'tagline' => 'Trust + Verification + Transparent Pricing',

    'escrow' => [
        'release_after_days' => 3,
    ],

    'verification' => [
        'fee_min_ksh' => 700,
        'fee_max_ksh' => 1000,
    ],

    'commission' => [
        'default_percent' => 5,
    ],

    'trust_cert' => [
        'valid_days' => 90,
    ],
];
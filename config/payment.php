<?php

return [
    'stripe' => [
        'key' => getenv('STRIPE_KEY') ?: 'pk_test_sample',
        'secret' => getenv('STRIPE_SECRET') ?: 'sk_test_sample',
        'webhook_secret' => getenv('STRIPE_WEBHOOK_SECRET') ?: 'whsec_test_mock_webhook_secret_12345',
    ],
    'currency' => getenv('DEFAULT_CURRENCY') ?: 'INR',
    'currency_symbol' => getenv('CURRENCY_SYMBOL') ?: '₹',
    'tax_rate_percent' => 18.0, // 18% GST
    'trial_days' => 14,
    'grace_period_days' => 3,
];
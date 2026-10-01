<?php

return [
    'name' => getenv('APP_NAME') ?: 'SaaSify Billing Engine',
    'env' => getenv('APP_ENV') ?: 'development',
    'debug' => filter_var(getenv('APP_DEBUG') ?: true, FILTER_VALIDATE_BOOLEAN),
    'url' => getenv('APP_URL') ?: 'http://localhost/saas-billing-engine/public',
    'timezone' => getenv('APP_TIMEZONE') ?: 'Asia/Kolkata',
];
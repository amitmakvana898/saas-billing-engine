<?php

use App\Core\Router;
use App\Controllers\WebhookController;
use App\Controllers\ApiController;
use App\Middlewares\ApiKeyMiddleware;

/** @var Router $router */

// Stripe Webhook Endpoint (Protected via Cryptographic Signature, no CSRF/Session)
$router->post('/api/webhooks/stripe', [WebhookController::class, 'handleStripe']);

// Public Developer API Documentation
$router->get('/api/v1/docs', [ApiController::class, 'getDocs']);

// Authenticated Developer REST API v1
$apiAuth = [ApiKeyMiddleware::class];

$router->get('/api/v1/invoices', [ApiController::class, 'getInvoices'], $apiAuth);
$router->post('/api/v1/invoices', [ApiController::class, 'createInvoice'], $apiAuth);
$router->get('/api/v1/invoices/{id}', [ApiController::class, 'getInvoice'], $apiAuth);
$router->get('/api/v1/customers', [ApiController::class, 'getCustomers'], $apiAuth);
$router->get('/api/v1/plans', [ApiController::class, 'getPlans'], $apiAuth);
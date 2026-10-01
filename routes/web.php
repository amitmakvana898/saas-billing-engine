<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\SubscriptionController;
use App\Controllers\InvoiceController;
use App\Controllers\CustomerController;
use App\Controllers\TeamController;
use App\Controllers\WebhookController;
use App\Controllers\SuperAdminController;
use App\Controllers\SettingsController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\TenantScopeMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\BillingAdminMiddleware;
use App\Middlewares\SuperAdminMiddleware;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\RateLimitMiddleware;

/** @var Router $router */

// Public / Guest Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login'], [RateLimitMiddleware::class, CsrfMiddleware::class]);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register'], [RateLimitMiddleware::class, CsrfMiddleware::class]);
$router->get('/logout', [AuthController::class, 'logout']);

// Self-Service Password Recovery
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword']);
$router->post('/forgot-password', [AuthController::class, 'sendPasswordReset'], [RateLimitMiddleware::class, CsrfMiddleware::class]);
$router->get('/reset-password/{token}', [AuthController::class, 'showResetPassword']);
$router->post('/reset-password/{token}', [AuthController::class, 'resetPassword'], [RateLimitMiddleware::class, CsrfMiddleware::class]);

// Public Client-Facing Payment Portal (No authentication required, accessed via unique token)
$router->get('/pay/{token}', [InvoiceController::class, 'publicPay']);
$router->post('/pay/{token}', [InvoiceController::class, 'processPayment'], [CsrfMiddleware::class]);

// Standard Tenant Protected Routes (Auth + Tenant Scoping)
$authScope = [AuthMiddleware::class, TenantScopeMiddleware::class];

$router->get('/dashboard', [DashboardController::class, 'index'], $authScope);
$router->get('/suspended', [DashboardController::class, 'suspended'], [AuthMiddleware::class]);

// Customer & Client Management
$router->get('/customers', [CustomerController::class, 'index'], $authScope);
$router->post('/customers', [CustomerController::class, 'store'], array_merge($authScope, [CsrfMiddleware::class]));
$router->post('/customers/update/{id}', [CustomerController::class, 'update'], array_merge($authScope, [CsrfMiddleware::class]));
$router->post('/customers/delete/{id}', [CustomerController::class, 'delete'], array_merge($authScope, [CsrfMiddleware::class]));
$router->get('/api/customers', [CustomerController::class, 'apiSearch'], $authScope);

// Invoices & B2B Tax Billing
$router->get('/invoices', [InvoiceController::class, 'index'], $authScope);
$router->get('/invoices/create', [InvoiceController::class, 'create'], $authScope);
$router->post('/invoices', [InvoiceController::class, 'store'], array_merge($authScope, [CsrfMiddleware::class]));
$router->get('/invoices/export', [InvoiceController::class, 'exportCsv'], $authScope);
$router->get('/invoices/{id}', [InvoiceController::class, 'view'], $authScope);
$router->get('/invoices/{id}/print', [InvoiceController::class, 'print'], $authScope);
$router->post('/invoices/{id}/record-payment', [InvoiceController::class, 'recordPayment'], array_merge($authScope, [CsrfMiddleware::class]));
$router->post('/invoices/{id}/void', [InvoiceController::class, 'voidInvoice'], array_merge($authScope, [CsrfMiddleware::class]));
$router->post('/invoices/{id}/send-email', [InvoiceController::class, 'sendEmail'], array_merge($authScope, [CsrfMiddleware::class]));

// Subscription Plans (Viewable by all tenant members, modifications strictly RBAC guarded)
$router->get('/plans', [SubscriptionController::class, 'showPlans'], $authScope);
$router->post('/plans/upgrade', [SubscriptionController::class, 'upgrade'], array_merge($authScope, [BillingAdminMiddleware::class, CsrfMiddleware::class]));
$router->post('/plans/cancel', [SubscriptionController::class, 'cancel'], array_merge($authScope, [BillingAdminMiddleware::class, CsrfMiddleware::class]));

// Team Management & RBAC
$router->get('/team', [TeamController::class, 'index'], $authScope);
$router->post('/team/invite', [TeamController::class, 'invite'], array_merge($authScope, [AdminMiddleware::class, CsrfMiddleware::class]));
$router->post('/team/delete/{id}', [TeamController::class, 'delete'], array_merge($authScope, [AdminMiddleware::class, CsrfMiddleware::class]));

// Organization Settings & Personal Profile
$router->get('/settings', [SettingsController::class, 'showSettings'], $authScope);
$router->post('/settings/company', [SettingsController::class, 'updateCompany'], array_merge($authScope, [AdminMiddleware::class, CsrfMiddleware::class]));
$router->post('/settings/profile', [SettingsController::class, 'updateProfile'], array_merge($authScope, [CsrfMiddleware::class]));
$router->post('/settings/api-keys', [SettingsController::class, 'generateApiKey'], array_merge($authScope, [AdminMiddleware::class, CsrfMiddleware::class]));
$router->post('/settings/api-keys/revoke/{id}', [SettingsController::class, 'revokeApiKey'], array_merge($authScope, [AdminMiddleware::class, CsrfMiddleware::class]));

// Webhook Event Testing Simulator (for local demo & interview showcase)
$router->get('/webhook-simulator', [WebhookController::class, 'showSimulator'], $authScope);

// ==========================================
// PLATFORM SUPER ADMIN PORTAL ROUTES
// ==========================================
$router->get('/admin', function() {
    return redirect('/admin/dashboard');
});
$router->get('/admin/login', [SuperAdminController::class, 'showLogin']);
$router->post('/admin/login', [SuperAdminController::class, 'login'], [RateLimitMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/logout', [SuperAdminController::class, 'logout']);

$adminScope = [SuperAdminMiddleware::class];

$router->get('/admin/dashboard', [SuperAdminController::class, 'dashboard'], $adminScope);
$router->get('/admin/tenants', [SuperAdminController::class, 'tenants'], $adminScope);
$router->post('/admin/tenants/status', [SuperAdminController::class, 'toggleTenantStatus'], array_merge($adminScope, [CsrfMiddleware::class]));
$router->get('/admin/webhooks', [SuperAdminController::class, 'webhooks'], $adminScope);
$router->get('/admin/profile', [SuperAdminController::class, 'profile'], $adminScope);
$router->post('/admin/profile', [SuperAdminController::class, 'updateProfile'], array_merge($adminScope, [CsrfMiddleware::class]));
$router->post('/admin/password', [SuperAdminController::class, 'updatePassword'], array_merge($adminScope, [CsrfMiddleware::class]));
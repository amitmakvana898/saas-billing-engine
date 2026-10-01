USE `saas_billing_db`;

-- Seed SaaS Plans
INSERT INTO `plans` (`id`, `name`, `slug`, `description`, `price_cents`, `billing_interval`, `features_json`, `max_seats`, `gateway_plan_id`, `is_active`)
VALUES
(1, 'Starter', 'starter-monthly', 'Ideal for early startups and small teams.', 2900, 'monthly', 
 '["Up to 5 Team Members", "10,000 API Requests/day", "Standard Email Support", "Automated PDF Invoices"]', 
 5, 'price_starter_monthly_01', 1),

(2, 'Professional', 'pro-monthly', 'Everything a growing company needs to scale operations.', 7900, 'monthly', 
 '["Up to 25 Team Members", "100,000 API Requests/day", "Priority 24/7 Support", "Custom Webhooks & Integrations", "Audit Logs"]', 
 25, 'price_pro_monthly_02', 1),

(3, 'Enterprise', 'enterprise-yearly', 'Full-throttle performance, custom SLA and dedicated infrastructure.', 29900, 'yearly', 
 '["Unlimited Team Members", "Unlimited API Requests", "Dedicated Account Manager", "Custom SLA & 99.99% Uptime", "Advanced Security & SSO"]', 
 999, 'price_enterprise_yearly_03', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price_cents`=VALUES(`price_cents`), `features_json`=VALUES(`features_json`), `max_seats`=VALUES(`max_seats`);

-- Seed Demo Tenant
INSERT INTO `tenants` (`id`, `name`, `subdomain`, `tax_id`, `billing_address`, `phone`, `status`, `created_at`)
VALUES
('tenant-demo-uuid-001', 'Acme Cloud Technologies', 'acme', '24AAACA1234A1Z5', 'Plot 45, SG Highway, Bodakdev, Ahmedabad, Gujarat 380054', '+91 98765 43210', 'active', NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `tax_id`=VALUES(`tax_id`), `billing_address`=VALUES(`billing_address`), `phone`=VALUES(`phone`);

-- Seed Demo User (password is: Password123!)
-- Hash generated using password_hash('Password123!', PASSWORD_BCRYPT)
INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `password_hash`, `role`, `created_at`)
VALUES
('user-demo-uuid-001', 'tenant-demo-uuid-001', 'John Doe (Acme CEO)', 'owner@acme.com', '$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS', 'owner', NOW()),
('user-demo-uuid-002', 'tenant-demo-uuid-001', 'Sarah Connor (Billing)', 'billing@acme.com', '$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS', 'billing_manager', NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed Demo Subscription for Acme (Professional Plan)
INSERT INTO `subscriptions` (`id`, `tenant_id`, `plan_id`, `gateway_subscription_id`, `status`, `trial_ends_at`, `current_period_start`, `current_period_end`, `created_at`)
VALUES
('sub-demo-uuid-001', 'tenant-demo-uuid-001', 2, 'sub_live_mock_acme_7900', 'active', NULL, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), NOW())
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

-- Seed Demo Invoices for Acme
INSERT INTO `invoices` (`id`, `tenant_id`, `subscription_id`, `invoice_number`, `subtotal_cents`, `tax_cents`, `amount_paid_cents`, `currency`, `status`, `billing_reason`, `paid_at`, `created_at`)
VALUES
('inv-demo-uuid-001', 'tenant-demo-uuid-001', 'sub-demo-uuid-001', 'INV-2026-0001', 7900, 1422, 9322, 'USD', 'paid', 'subscription_create', NOW(), NOW()),
('inv-demo-uuid-002', 'tenant-demo-uuid-001', 'sub-demo-uuid-001', 'INV-2026-0002', 7900, 1422, 9322, 'USD', 'paid', 'subscription_cycle', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY))
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

-- Seed Platform Super Admin (password is: AdminPass123!)
INSERT INTO `super_admins` (`id`, `name`, `email`, `password_hash`, `created_at`)
VALUES
('superadmin-uuid-001', 'Platform Super Admin', 'superadmin@saasify.app', '$2y$12$TvK1kTkWnSabK7DEIVZpGeb.LsWLgQvc5zxJwKWGA0LBmTRNePasy', NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `password_hash`=VALUES(`password_hash`);
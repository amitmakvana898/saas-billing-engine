<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Repositories\TenantRepository;
use App\Repositories\WebhookRepository;
use App\Repositories\PlanRepository;
use PDO;

class SuperAdminController
{
    private TenantRepository $tenantRepo;
    private WebhookRepository $webhookRepo;
    private PlanRepository $planRepo;

    public function __construct()
    {
        $this->tenantRepo = new TenantRepository();
        $this->webhookRepo = new WebhookRepository();
        $this->planRepo = new PlanRepository();
    }

    public function showLogin(Request $request): Response
    {
        if (Session::has('super_admin')) {
            return redirect('/admin/dashboard');
        }
        return view('admin.login', [
            'title' => 'Platform Super Admin Authentication - SaaSify'
        ], null);
    }

    public function login(Request $request): Response
    {
        $email = strtolower(trim($request->input('email') ?? ''));
        $password = $request->input('password') ?? '';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM `super_admins` WHERE `email` = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Prevent Session Fixation
            Session::regenerate(true);

            Session::set('super_admin', [
                'id' => $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'logged_in_at' => date('Y-m-d H:i:s'),
            ]);
            flash('success', 'Authenticated as Platform Super Administrator.');
            return redirect('/admin/dashboard');
        }

        flash('error', 'Invalid Super Administrator credentials.');
        return redirect('/admin/login');
    }

    public function logout(Request $request): Response
    {
        Session::remove('super_admin');
        flash('success', 'Super Administrator session ended.');
        return redirect('/');
    }

    public function dashboard(Request $request): Response
    {
        $pdo = Database::getConnection();

        // 1. Calculate Global Platform MRR
        // Active monthly subs + yearly subs divided by 12
        $mrrSql = "
            SELECT 
                COALESCE(SUM(
                    CASE 
                        WHEN p.billing_interval = 'monthly' THEN p.price_cents
                        WHEN p.billing_interval = 'yearly' THEN ROUND(p.price_cents / 12)
                        ELSE 0
                    END
                ), 0) as mrr_cents
            FROM `subscriptions` s
            JOIN `plans` p ON p.id = s.plan_id
            WHERE s.status = 'active'
        ";
        $mrrCents = (int)$pdo->query($mrrSql)->fetchColumn();

        // 2. Total Revenue Collected across all tenants
        $revSql = "SELECT COALESCE(SUM(amount_paid_cents), 0) FROM `invoices` WHERE status = 'paid'";
        $totalRevenueCents = (int)$pdo->query($revSql)->fetchColumn();

        // 3. Subscription counts by status
        $subCountSql = "
            SELECT status, COUNT(*) as count 
            FROM `subscriptions` 
            GROUP BY status
        ";
        $subStats = $pdo->query($subCountSql)->fetchAll(PDO::FETCH_KEY_PAIR);

        // 4. All Tenants with details
        $allTenants = $this->tenantRepo->getAllWithSubscriptionDetails();

        // 5. Recent Webhook Events
        $recentWebhooks = $this->webhookRepo->listAll(6);

        return view('admin.dashboard', [
            'title' => 'Executive Platform Overview - Super Admin',
            'mrrCents' => $mrrCents,
            'totalRevenueCents' => $totalRevenueCents,
            'totalTenants' => count($allTenants),
            'activeSubs' => (int)($subStats['active'] ?? 0),
            'trialingSubs' => (int)($subStats['trialing'] ?? 0),
            'tenants' => array_slice($allTenants, 0, 5),
            'webhooks' => $recentWebhooks,
            'admin' => Session::get('super_admin'),
        ], 'admin');
    }

    public function tenants(Request $request): Response
    {
        $allTenants = $this->tenantRepo->getAllWithSubscriptionDetails();

        return view('admin.tenants', [
            'title' => 'Organizations & Tenants Directory - Super Admin',
            'tenants' => $allTenants,
            'admin' => Session::get('super_admin'),
        ], 'admin');
    }

    public function toggleTenantStatus(Request $request): Response
    {
        $tenantId = $request->input('tenant_id');
        $newStatus = $request->input('status'); // 'active', 'suspended', 'trial'

        if ($tenantId && in_array($newStatus, ['active', 'suspended', 'trial'], true)) {
            $this->tenantRepo->updateStatus($tenantId, $newStatus);
            flash('success', "Tenant status successfully updated to [{$newStatus}].");
        } else {
            flash('error', 'Invalid tenant status request.');
        }

        return redirect('/admin/tenants');
    }

    public function webhooks(Request $request): Response
    {
        $events = $this->webhookRepo->listAll(100);

        return view('admin.webhooks', [
            'title' => 'Global Webhook & Idempotency Monitor - Super Admin',
            'events' => $events,
            'admin' => Session::get('super_admin'),
        ], 'admin');
    }

    public function profile(Request $request): Response
    {
        $sessionAdmin = Session::get('super_admin');
        if (!$sessionAdmin) {
            return redirect('/admin/login');
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM `super_admins` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $sessionAdmin['id'] ?? '']);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin && !empty($sessionAdmin['email'])) {
            $stmt = $pdo->prepare('SELECT * FROM `super_admins` WHERE `email` = :email LIMIT 1');
            $stmt->execute(['email' => $sessionAdmin['email']]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Recent platform audit logs
        $auditLogs = [];
        try {
            $stmtAudit = $pdo->query("SELECT * FROM `audit_logs` ORDER BY `created_at` DESC LIMIT 6");
            $auditLogs = $stmtAudit ? $stmtAudit->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (\Exception $e) {
            $auditLogs = [];
        }

        return view('admin.profile', [
            'title' => 'Administrator Profile & Security - SaaSify Master Control',
            'admin' => $admin ?: $sessionAdmin,
            'auditLogs' => $auditLogs,
        ], 'admin');
    }

    public function updateProfile(Request $request): Response
    {
        $sessionAdmin = Session::get('super_admin');
        if (!$sessionAdmin) {
            return redirect('/admin/login');
        }

        $name = trim($request->input('name') ?? '');
        $email = strtolower(trim($request->input('email') ?? ''));

        if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please provide a valid administrator name and email address.');
            return redirect('/admin/profile');
        }

        $pdo = Database::getConnection();

        // Check if email taken by another super admin
        $stmt = $pdo->prepare('SELECT id FROM `super_admins` WHERE `email` = :email AND `id` != :id LIMIT 1');
        $stmt->execute([
            'email' => $email,
            'id' => $sessionAdmin['id'] ?? '',
        ]);
        if ($stmt->fetch()) {
            flash('error', 'Another administrator account is already registered with this email.');
            return redirect('/admin/profile');
        }

        // Update profile
        $update = $pdo->prepare('UPDATE `super_admins` SET `name` = :name, `email` = :email WHERE `id` = :id');
        $update->execute([
            'name' => $name,
            'email' => $email,
            'id' => $sessionAdmin['id'] ?? '',
        ]);

        // Update session
        $sessionAdmin['name'] = $name;
        $sessionAdmin['email'] = $email;
        Session::set('super_admin', $sessionAdmin);

        flash('success', 'Super Administrator profile updated successfully.');
        return redirect('/admin/profile');
    }

    public function updatePassword(Request $request): Response
    {
        $sessionAdmin = Session::get('super_admin');
        if (!$sessionAdmin) {
            return redirect('/admin/login');
        }

        $currentPassword = (string)($request->input('current_password') ?? '');
        $newPassword = (string)($request->input('new_password') ?? '');
        $confirmPassword = (string)($request->input('confirm_password') ?? '');

        if (empty($currentPassword) || empty($newPassword)) {
            flash('error', 'Current password and new password are required.');
            return redirect('/admin/profile');
        }

        if (strlen($newPassword) < 8) {
            flash('error', 'New password must be at least 8 characters long for platform security.');
            return redirect('/admin/profile');
        }

        if ($newPassword !== $confirmPassword) {
            flash('error', 'New password and confirmation password do not match.');
            return redirect('/admin/profile');
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM `super_admins` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $sessionAdmin['id'] ?? '']);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
            flash('error', 'Authentication failed: The current password you entered is incorrect.');
            return redirect('/admin/profile');
        }

        // Update password hash
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $update = $pdo->prepare('UPDATE `super_admins` SET `password_hash` = :hash WHERE `id` = :id');
        $update->execute([
            'hash' => $newHash,
            'id' => $sessionAdmin['id'] ?? '',
        ]);

        // Record audit entry
        try {
            $audit = $pdo->prepare("
                INSERT INTO `audit_logs` (`tenant_id`, `user_id`, `user_name`, `action`, `description`, `created_at`)
                VALUES ('SYSTEM', :uid, :uname, 'ADMIN_PASSWORD_CHANGED', 'Platform Super Admin changed master password credentials.', NOW())
            ");
            $audit->execute([
                'uid' => $admin['id'],
                'uname' => $admin['name'] . ' (' . $admin['email'] . ')',
            ]);
        } catch (\Exception $e) {
            // Silently continue
        }

        flash('success', 'Password successfully updated! Your Super Administrator security credentials have been refreshed.');
        return redirect('/admin/profile');
    }
}
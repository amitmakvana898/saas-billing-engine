<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\PlanRepository;
use App\Core\Session;
use App\Core\Database;

class AuthService
{
    private TenantRepository $tenantRepo;
    private UserRepository $userRepo;
    private SubscriptionRepository $subRepo;
    private PlanRepository $planRepo;

    public function __construct()
    {
        $this->tenantRepo = new TenantRepository();
        $this->userRepo = new UserRepository();
        $this->subRepo = new SubscriptionRepository();
        $this->planRepo = new PlanRepository();
    }

    /**
     * Atomically register new Tenant organization, Owner user, and 14-day Free Trial subscription
     */
    public function registerTenant(array $data): array
    {
        $companyName = trim($data['company_name'] ?? '');
        $subdomain = strtolower(trim($data['subdomain'] ?? ''));
        $userName = trim($data['name'] ?? '');
        $email = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if (empty($companyName) || empty($subdomain) || empty($email) || empty($password)) {
            throw new \InvalidArgumentException('All registration fields are strictly required.');
        }

        if (!preg_match('/^[a-z0-9-]+$/', $subdomain)) {
            throw new \InvalidArgumentException('Subdomain must contain only lowercase letters, numbers, and hyphens.');
        }

        if ($this->tenantRepo->findBySubdomain($subdomain)) {
            throw new \InvalidArgumentException('This organization subdomain is already taken.');
        }

        if ($this->userRepo->findByEmail($email)) {
            throw new \InvalidArgumentException('A user with this email already exists.');
        }

        return Database::transaction(function() use ($companyName, $subdomain, $userName, $email, $password) {
            // 1. Create Tenant
            $tenantId = $this->tenantRepo->create([
                'name' => $companyName,
                'subdomain' => $subdomain,
                'status' => 'trial',
            ]);

            // 2. Create Owner User
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $userId = $this->userRepo->create([
                'tenant_id' => $tenantId,
                'name' => $userName,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => 'owner',
            ]);

            // 3. Attach Default 14-Day Free Trial Subscription on Starter Plan (Plan ID 1)
            $starterPlan = $this->planRepo->findById(1);
            $planId = $starterPlan ? $starterPlan['id'] : 1;
            
            $now = date('Y-m-d H:i:s');
            $trialEnd = date('Y-m-d H:i:s', strtotime('+14 days'));

            $this->subRepo->create([
                'tenant_id' => $tenantId,
                'plan_id' => $planId,
                'status' => 'trialing',
                'trial_ends_at' => $trialEnd,
                'current_period_start' => $now,
                'current_period_end' => $trialEnd,
            ]);

            return [
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'email' => $email,
                'name' => $userName,
            ];
        });
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        // Prevent Session Fixation
        Session::regenerate(true);

        // Set session
        Session::set('user', [
            'id' => $user['id'],
            'tenant_id' => $user['tenant_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        $tenant = $this->tenantRepo->findById($user['tenant_id']);
        Session::set('tenant', $tenant);

        return true;
    }

    public function logout(): void
    {
        Session::destroy();
    }
}
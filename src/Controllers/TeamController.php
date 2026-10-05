<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\UserRepository;
use App\Services\SubscriptionService;

class TeamController
{
    private UserRepository $userRepo;
    private SubscriptionService $subService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->subService = new SubscriptionService();
    }

    public function index(Request $request): Response
    {
        $tenant = current_tenant();
        $members = $this->userRepo->listByTenant($tenant['id']);
        $sub = $this->subService->getTenantSubscription($tenant['id']);
        $maxSeats = (int)($sub['max_seats'] ?? 5);
        $usedSeats = count($members);

        $roleCounts = [
            'owner' => 0,
            'admin' => 0,
            'billing_manager' => 0,
            'member' => 0,
        ];
        foreach ($members as $m) {
            $r = $m['role'] ?? 'member';
            if (isset($roleCounts[$r])) {
                $roleCounts[$r]++;
            }
        }

        return view('team.index', [
            'title' => 'Team & Role-Based Access Control (RBAC) - SaaSify',
            'tenant' => $tenant,
            'members' => $members,
            'roleCounts' => $roleCounts,
            'maxSeats' => $maxSeats,
            'usedSeats' => $usedSeats,
            'subscription' => $sub,
            'currentUser' => auth_user(),
        ]);
    }

    public function invite(Request $request): Response
    {
        if (!can_manage_team()) {
            flash('error', 'Unauthorized. Only Workspace Owners and Admins can invite team members.');
            return redirect('/team');
        }

        $tenant = current_tenant();
        $name = trim($request->input('name') ?? '');
        $email = strtolower(trim($request->input('email') ?? ''));
        $password = $request->input('password') ?? '';
        $role = strtolower(trim($request->input('role') ?? 'member'));

        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'All fields (Name, Email, Password) are required.');
            return redirect('/team');
        }

        $validRoles = ['admin', 'billing_manager', 'member'];
        if (!in_array($role, $validRoles, true)) {
            flash('error', 'Invalid role assigned. Allowed: Admin, Billing Manager, or Member.');
            return redirect('/team');
        }

        // Quota check: Enforce Plan Seat Limits
        $sub = $this->subService->getTenantSubscription($tenant['id']);
        $maxSeats = (int)($sub['max_seats'] ?? 5);
        $currentMembers = $this->userRepo->listByTenant($tenant['id']);
        if (count($currentMembers) >= $maxSeats) {
            flash('error', "Seat limit reached! Your current '{$sub['plan_name']}' plan permits up to {$maxSeats} team members. Please upgrade your subscription plan to add more members.");
            return redirect('/team');
        }

        // Check if email exists in this tenant or globally
        if ($this->userRepo->findByEmail($email)) {
            flash('error', 'A user with this email address already exists.');
            return redirect('/team');
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userRepo->create([
            'tenant_id' => $tenant['id'],
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash,
            'role' => $role,
        ]);

        audit_log('team_member_invited', "Invited {$name} ({$email}) with role " . strtoupper($role));

        flash('success', "Team member {$name} ({$role}) invited and activated successfully!");
        return redirect('/team');
    }

    public function delete(Request $request, string $id): Response
    {
        if (!can_manage_team()) {
            flash('error', 'Unauthorized. Only Workspace Owners and Admins can remove team members.');
            return redirect('/team');
        }

        $tenant = current_tenant();
        $currentUser = auth_user();

        if ($currentUser['id'] === $id) {
            flash('error', 'You cannot remove your own active account.');
            return redirect('/team');
        }

        $targetUser = $this->userRepo->findByTenantAndId($tenant['id'], $id);
        if (!$targetUser) {
            flash('error', 'User not found in your organization.');
            return redirect('/team');
        }

        if ($targetUser['role'] === 'owner') {
            flash('error', 'Organization Owner cannot be removed.');
            return redirect('/team');
        }

        $this->userRepo->delete($id, $tenant['id']);
        audit_log('team_member_removed', "Removed team member {$targetUser['name']} ({$targetUser['email']})");

        flash('success', "Team member {$targetUser['name']} has been removed from organization.");
        return redirect('/team');
    }
}
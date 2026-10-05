<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use App\Repositories\ApiKeyRepository;

class SettingsController
{
    private TenantRepository $tenantRepo;
    private UserRepository $userRepo;
    private ApiKeyRepository $apiKeyRepo;

    public function __construct()
    {
        $this->tenantRepo = new TenantRepository();
        $this->userRepo = new UserRepository();
        $this->apiKeyRepo = new ApiKeyRepository();
    }

    public function showSettings(Request $request): Response
    {
        $tenantSession = current_tenant();
        $userSession = auth_user();

        // Fetch fresh records from DB
        $tenant = $this->tenantRepo->findById($tenantSession['id']);
        $user = $this->userRepo->findById($userSession['id']);
        $apiKeys = $this->apiKeyRepo->listByTenant($tenantSession['id']);

        return view('settings.index', [
            'title' => 'Settings & GST Profile - SaaSify',
            'tenant' => $tenant,
            'user' => $user,
            'apiKeys' => $apiKeys,
        ]);
    }

    public function updateCompany(Request $request): Response
    {
        if (!can_manage_team()) {
            flash('error', 'Unauthorized. Only Organization Owners and Admins can update company billing settings.');
            return redirect('/settings');
        }

        $tenantSession = current_tenant();
        $tenantId = $tenantSession['id'];

        $name = trim($request->input('name') ?? '');
        $taxId = strtoupper(trim($request->input('tax_id') ?? ''));
        $billingAddress = trim($request->input('billing_address') ?? '');
        $phone = trim($request->input('phone') ?? '');
        $logoUrl = trim($request->input('logo_url') ?? '');

        // Handle direct file upload for company brand logo
        if (!empty($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['logo_file']['tmp_name'];
            $origName = $_FILES['logo_file']['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowedExts = ['png', 'jpg', 'jpeg', 'svg', 'webp'];

            if (in_array($ext, $allowedExts, true) && filesize($tmpPath) <= 2 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../../public/uploads/logos';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                $safeName = 'logo_' . substr(md5($tenantId), 0, 10) . '_' . time() . '.' . $ext;
                $destPath = $uploadDir . '/' . $safeName;
                if (move_uploaded_file($tmpPath, $destPath) || @copy($tmpPath, $destPath)) {
                    $logoUrl = app_url('/uploads/logos/' . $safeName);
                }
            }
        }

        // If no new logo file and no new logo URL entered, retain existing
        if (empty($logoUrl)) {
            $existingTenant = $this->tenantRepo->findById($tenantId);
            $logoUrl = $existingTenant['logo_url'] ?? '';
        }

        $bankName = trim($request->input('bank_name') ?? '');
        $bankAccountNo = trim($request->input('bank_account_no') ?? '');
        $bankIfsc = strtoupper(trim($request->input('bank_ifsc') ?? ''));
        $upiId = trim($request->input('upi_id') ?? '');

        if (empty($name)) {
            flash('error', 'Company / Organization name cannot be empty.');
            return redirect('/settings');
        }

        $this->tenantRepo->updateProfile($tenantId, [
            'name' => $name,
            'tax_id' => $taxId,
            'billing_address' => $billingAddress,
            'phone' => $phone,
            'logo_url' => $logoUrl,
            'bank_name' => $bankName,
            'bank_account_no' => $bankAccountNo,
            'bank_ifsc' => $bankIfsc,
            'upi_id' => $upiId,
        ]);

        // Refresh session
        $updatedTenant = $this->tenantRepo->findById($tenantId);
        Session::set('tenant', $updatedTenant);

        audit_log('company_settings_updated', "Updated company profile, phone ({$phone}), and GSTIN ({$taxId})");

        flash('success', 'Company profile and GST billing details updated successfully!');
        return redirect('/settings');
    }

    public function updateProfile(Request $request): Response
    {
        $userSession = auth_user();
        $tenantSession = current_tenant();
        $user = $this->userRepo->findById($userSession['id']);

        $name = trim($request->input('name') ?? '');
        $currentPassword = $request->input('current_password') ?? '';
        $newPassword = $request->input('new_password') ?? '';
        $confirmPassword = $request->input('confirm_password') ?? '';

        if (empty($name)) {
            flash('error', 'Display name cannot be empty.');
            return redirect('/settings');
        }

        // Update display name
        $this->userRepo->updateProfile($user['id'], $tenantSession['id'], $name);
        $user['name'] = $name;

        // If user wants to change password
        if (!empty($newPassword)) {
            if (empty($currentPassword) || !password_verify($currentPassword, $user['password_hash'])) {
                flash('error', 'Current password is incorrect. Password was not changed.');
                return redirect('/settings');
            }

            if ($newPassword !== $confirmPassword) {
                flash('error', 'New password and confirmation do not match.');
                return redirect('/settings');
            }

            if (strlen($newPassword) < 8) {
                flash('error', 'New password must be at least 8 characters long.');
                return redirect('/settings');
            }

            $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            $this->userRepo->updatePassword($user['id'], $hash);
            audit_log('password_changed', "Updated user account password");
        }

        // Refresh session
        Session::set('user', $user);

        flash('success', 'Personal profile updated successfully!');
        return redirect('/settings');
    }

    public function generateApiKey(Request $request): Response
    {
        if (!can_manage_team()) {
            flash('error', 'Unauthorized. Only Organization Owners and Admins can create API keys.');
            return redirect('/settings');
        }

        $tenantSession = current_tenant();
        $name = trim($request->input('key_name') ?? 'Default API Key');

        if (empty($name)) {
            $name = 'Production API Key';
        }

        $rawKey = 'ak_live_' . bin2hex(random_bytes(20));
        $prefix = substr($rawKey, 0, 14) . '...';
        $hash = hash('sha256', $rawKey);

        $this->apiKeyRepo->create($tenantSession['id'], $name, $prefix, $hash);

        Session::flash('new_api_key', $rawKey);
        audit_log('api_key_generated', "Generated new API key '{$name}' with prefix {$prefix}");

        flash('success', "API Key '{$name}' generated successfully! Please copy it now.");
        return redirect('/settings');
    }

    public function revokeApiKey(Request $request, string $id): Response
    {
        if (!can_manage_team()) {
            flash('error', 'Unauthorized. Only Organization Owners and Admins can revoke API keys.');
            return redirect('/settings');
        }

        $tenantSession = current_tenant();
        $this->apiKeyRepo->revoke($id, $tenantSession['id']);

        audit_log('api_key_revoked', "Revoked API key ID {$id}");
        flash('success', 'API Key has been revoked and can no longer be used.');
        return redirect('/settings');
    }
}

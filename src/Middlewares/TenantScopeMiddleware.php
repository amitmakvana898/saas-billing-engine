<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\TenantRepository;

class TenantScopeMiddleware
{
    public function handle(Request $request): ?Response
    {
        $user = Session::get('user');
        if (!$user || empty($user['tenant_id'])) {
            return Response::redirect(app_url('/login'));
        }

        // Always resolve live tenant context from DB to enforce current account status
        $tenantRepo = new TenantRepository();
        $tenant = $tenantRepo->findById($user['tenant_id']);

        if (!$tenant) {
            Session::destroy();
            Session::flash('error', 'Organization tenant account not found.');
            return Response::redirect(app_url('/login'));
        }

        Session::set('tenant', $tenant);

        if ($tenant['status'] === 'suspended') {
            $path = $request->getPath();
            // Allowed routes for suspended organizations so they can view invoices, plans, or logout
            $allowed = ['/suspended', '/invoices', '/plans', '/plans/upgrade', '/logout'];
            $isAllowed = false;
            foreach ($allowed as $prefix) {
                if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                    $isAllowed = true;
                    break;
                }
            }
            if (!$isAllowed) {
                return Response::redirect(app_url('/suspended'));
            }
        }

        return null;
    }
}
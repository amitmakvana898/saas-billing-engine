<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class BillingAdminMiddleware
{
    public function handle(Request $request): ?Response
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role'], ['owner', 'admin', 'billing_manager'], true)) {
            Session::flash('error', 'Access Denied: Only Billing Managers, Admins, or Owners can modify subscription plans.');
            return Response::redirect(app_url('/plans'));
        }
        return null;
    }
}
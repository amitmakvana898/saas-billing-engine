<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class SuperAdminMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (!Session::has('super_admin')) {
            Session::flash('error', 'Super Administrator authentication required to access this portal.');
            return Response::redirect(app_url('/admin/login'));
        }
        return null;
    }
}
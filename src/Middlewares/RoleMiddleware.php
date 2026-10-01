<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RoleMiddleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = ['owner', 'admin'])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request): ?Response
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role'], $this->allowedRoles, true)) {
            Session::flash('error', 'Unauthorized action: Insufficient permissions for this module.');
            return Response::redirect(app_url('/dashboard'));
        }
        return null;
    }
}
<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(Request $request): ?Response
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role'], ['owner', 'admin'], true)) {
            Session::flash('error', 'Access Denied: Only organization Owners and Admins can manage team members.');
            return Response::redirect(app_url('/dashboard'));
        }
        return null;
    }
}
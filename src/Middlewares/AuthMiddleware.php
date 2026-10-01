<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (!Session::has('user')) {
            Session::flash('error', 'Please log in to access this page.');
            return Response::redirect(app_url('/login'));
        }
        return null;
    }
}
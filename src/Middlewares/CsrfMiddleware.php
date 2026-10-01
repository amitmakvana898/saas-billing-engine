<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request): ?Response
    {
        if ($request->getMethod() === 'POST') {
            $token = $request->input('_csrf_token');
            if (!Session::validateCsrf($token)) {
                return Response::html('<h1>403 Forbidden: Invalid or expired CSRF token.</h1>', 403);
            }
        }
        return null;
    }
}
<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\ApiKeyRepository;

class ApiKeyMiddleware
{
    private ApiKeyRepository $apiKeyRepo;

    public function __construct()
    {
        $this->apiKeyRepo = new ApiKeyRepository();
    }

    public function handle(Request $request): ?Response
    {
        $authHeader = $request->getHeader('Authorization') 
            ?? $_SERVER['HTTP_AUTHORIZATION'] 
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
            ?? '';

        $apiKey = '';
        if (preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
            $apiKey = trim($matches[1]);
        } else {
            $apiKey = $request->getHeader('X-API-Key') ?? $request->input('api_key') ?? '';
        }

        if (empty($apiKey)) {
            return Response::json([
                'success' => false,
                'error' => 'Authentication required. Provide your API key via "Authorization: Bearer ak_live_..." header.',
                'documentation' => '/api/v1/docs'
            ], 401);
        }

        $keyHash = hash('sha256', $apiKey);
        $keyRecord = $this->apiKeyRepo->findByHash($keyHash);

        if (!$keyRecord) {
            return Response::json([
                'success' => false,
                'error' => 'Invalid or revoked API key.',
            ], 403);
        }

        // Update last used timestamp
        $this->apiKeyRepo->updateLastUsed($keyRecord['id']);

        // Set tenant session / context
        Session::set('tenant_id', $keyRecord['tenant_id']);
        Session::set('api_tenant', [
            'id' => $keyRecord['tenant_id'],
            'name' => $keyRecord['tenant_name'],
            'subdomain' => $keyRecord['subdomain'],
        ]);

        return null;
    }
}

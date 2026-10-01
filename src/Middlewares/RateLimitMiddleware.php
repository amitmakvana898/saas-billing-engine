<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RateLimitMiddleware
{
    private int $maxAttempts;
    private int $decaySeconds;

    public function __construct(int $maxAttempts = 15, int $decaySeconds = 60)
    {
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function handle(Request $request): ?Response
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ip = trim(explode(',', $ip)[0]);
        $path = $request->getPath();

        $key = 'saas_rl_' . md5($ip . '_' . $path);
        $file = sys_get_temp_dir() . '/' . $key . '.json';

        $data = ['attempts' => 0, 'first_attempt' => time()];
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            $parsed = @json_decode($content, true);
            if (is_array($parsed) && isset($parsed['attempts'], $parsed['first_attempt'])) {
                $data = $parsed;
            }
        }

        // Reset window if decay period has passed
        if (time() - $data['first_attempt'] > $this->decaySeconds) {
            $data = ['attempts' => 0, 'first_attempt' => time()];
        }

        $data['attempts']++;
        @file_put_contents($file, json_encode($data), LOCK_EX);

        if ($data['attempts'] > $this->maxAttempts) {
            $retryAfter = max(1, $this->decaySeconds - (time() - $data['first_attempt']));
            
            if ($request->isMethod('POST')) {
                Session::flash('error', "Too many attempts detected. Please wait {$retryAfter} seconds before trying again.");
                return Response::redirect($path);
            }

            return new Response("Too Many Requests. Rate limit exceeded. Please retry after {$retryAfter} seconds.", 429, [
                'Retry-After' => (string)$retryAfter,
                'Content-Type' => 'text/plain; charset=UTF-8'
            ]);
        }

        return null;
    }
}

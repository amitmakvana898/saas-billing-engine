<?php

use App\Core\Session;
use App\Core\Response;
use App\Core\View;

// Polyfills for PHP < 8.0
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $val = getenv($key);
        if ($val === false) {
            return $default;
        }
        $lower = strtolower($val);
        switch ($lower) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
            default:
                return $val;
        }
    }
}

if (!function_exists('app_url')) {
    function app_url(string $path = ''): string {
        $base = rtrim(env('APP_URL', 'http://localhost/saas-billing-engine'), '/');
        $path = ltrim($path, '/');
        return $path ? "$base/$path" : $base;
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = [], ?string $layout = 'main'): Response {
        $content = View::render($template, $data, $layout);
        return Response::html($content);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): Response {
        return Response::redirect(app_url($path));
    }
}

if (!function_exists('json_response')) {
    function json_response($data, int $status = 200): Response {
        return Response::json($data, $status);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array {
        return Session::get('user');
    }
}

if (!function_exists('current_tenant')) {
    function current_tenant(): ?array {
        return Session::get('tenant');
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        return Session::has('user');
    }
}

// === RBAC Permission Helpers ===

if (!function_exists('user_role')) {
    function user_role(): string {
        $user = auth_user();
        return $user['role'] ?? 'member';
    }
}

if (!function_exists('is_owner')) {
    function is_owner(): bool {
        return user_role() === 'owner';
    }
}

if (!function_exists('can_manage_billing')) {
    function can_manage_billing(): bool {
        $role = user_role();
        return in_array($role, ['owner', 'admin', 'billing_manager'], true);
    }
}

if (!function_exists('can_manage_team')) {
    function can_manage_team(): bool {
        $role = user_role();
        return in_array($role, ['owner', 'admin'], true);
    }
}

// === Indian Rupee (INR / ₹) Currency Formatter ===
if (!function_exists('format_cents')) {
    function format_cents(int $cents, string $currency = 'INR'): string {
        $amount = $cents / 100;
        $curr = strtoupper($currency);
        
        if ($curr === 'INR' || $curr === '₹') {
            return '₹' . number_format($amount, 2);
        } elseif ($curr === 'USD') {
            return '$' . number_format($amount, 2);
        } else {
            return $currency . ' ' . number_format($amount, 2);
        }
    }
}

if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        $old = Session::get('_old_input', []);
        return $old[$key] ?? $default;
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin(): bool {
        return Session::has('super_admin');
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string {
        if ($message !== null) {
            Session::flash($key, $message);
            return null;
        }
        return Session::getFlash($key);
    }
}

if (!function_exists('audit_log')) {
    function audit_log(string $action, string $description): void {
        $tenant = current_tenant();
        if (!$tenant) {
            return;
        }
        $user = auth_user();
        $auditRepo = new \App\Repositories\AuditLogRepository();
        $auditRepo->log(
            $tenant['id'],
            $user['id'] ?? null,
            $user['name'] ?? null,
            $action,
            $description
        );
    }
}
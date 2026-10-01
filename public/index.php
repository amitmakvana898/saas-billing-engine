<?php

declare(strict_types=1);

// 1. Load Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Load .env Configuration
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
        }
    }
}

// 3. Robust Error Reporting Configuration
$appDebug = filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN);
$appEnv = env('APP_ENV', 'production');

if ($appDebug && $appEnv !== 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

// 4. Timezone
date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Kolkata'));

// 5. Initialize Request & Router
use App\Core\Request;
use App\Core\Router;

$request = new Request();
$router = new Router();

// 6. Register Web & API Routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// 7. Dispatch Request through Pipeline
try {
    $response = $router->dispatch($request);
    $response->send();
} catch (\Throwable $e) {
    // Log exception details safely to server logs
    error_log("[SaaSify Error] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

    http_response_code(500);

    if ($appDebug && $appEnv !== 'production') {
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>500 Debug Error</title><script src='https://cdn.tailwindcss.com'></script></head>";
        echo "<body class='bg-slate-900 text-slate-100 p-8 font-sans'>";
        echo "<div class='max-w-4xl mx-auto bg-slate-950 p-8 rounded-2xl border border-rose-800 shadow-2xl'>";
        echo "<h1 class='text-2xl font-bold text-rose-500 mb-2'>Unhandled Exception: " . htmlspecialchars($e->getMessage()) . "</h1>";
        echo "<p class='text-xs text-slate-400 font-mono mb-4'>In " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
        echo "<pre class='bg-slate-900 p-4 rounded-xl text-xs overflow-x-auto text-slate-300 font-mono leading-relaxed'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "<div class='mt-6'><a href='" . app_url('/') . "' class='px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold'>Return to Safety</a></div>";
        echo "</div></body></html>";
    } else {
        echo \App\Core\View::render('errors.500', [], null);
    }
}
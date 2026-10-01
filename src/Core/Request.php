<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private string $path;
    private array $queryParams;
    private array $postData;
    private array $headers;
    private $rawBody;

    public function __construct(?string $method = null, ?string $uri = null, array $queryParams = [], array $postData = [], array $headers = [])
    {
        $this->method = strtoupper($method ?? $_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->queryParams = !empty($queryParams) ? $queryParams : $_GET;
        $this->postData = !empty($postData) ? $postData : $_POST;
        $this->headers = !empty($headers) ? $headers : (function_exists('getallheaders') ? getallheaders() : []);
        $this->rawBody = file_get_contents('php://input');

        if ($uri !== null) {
            $parsed = parse_url($uri, PHP_URL_PATH);
            $this->path = '/' . trim($parsed, '/');
            $this->uri = $uri;
            return;
        }

        // Robust path resolution across direct directory access, base project folder, and mod_rewrite
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            $uri = $_GET['url'];
        } else {
            $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
            $scriptDir = dirname($scriptName);
            $scriptDir = ($scriptDir === '/' || $scriptDir === '.') ? '' : $scriptDir;
            $parentDir = dirname($scriptDir);
            $parentDir = ($parentDir === '/' || $parentDir === '.') ? '' : $parentDir;

            $reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

            if ($scriptDir !== '' && strpos($reqPath, $scriptDir) === 0) {
                $uri = substr($reqPath, strlen($scriptDir));
            } elseif ($parentDir !== '' && strpos($reqPath, $parentDir) === 0) {
                $uri = substr($reqPath, strlen($parentDir));
            } else {
                $uri = $reqPath;
            }
        }

        $parsed = parse_url($uri, PHP_URL_PATH);
        $this->path = '/' . trim($parsed, '/');
        $this->uri = $uri;

        // If JSON payload was posted
        if (str_contains($this->getHeader('Content-Type') ?? '', 'application/json')) {
            $decoded = json_decode($this->rawBody, true);
            if (is_array($decoded)) {
                $this->postData = array_merge($this->postData, $decoded);
            }
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($this->method) === strtoupper($method);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function input(string $key, $default = null)
    {
        return $this->postData[$key] ?? $this->queryParams[$key] ?? $default;
    }

    public function query(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->postData);
    }

    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $key => $val) {
            if (strcasecmp($key, $name) === 0) {
                return $val;
            }
        }
        return null;
    }

    public function getRawBody(): string
    {
        return $this->rawBody ?: '';
    }

    public function getClientIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
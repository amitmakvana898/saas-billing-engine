<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => '/' . trim($path, '/'),
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getPath();
        $effectiveMethod = ($requestMethod === 'HEAD') ? 'GET' : $requestMethod;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $effectiveMethod) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestPath, $matches)) {
                // Extract named parameter arguments
                $params = array_filter($matches, fn($k) => !is_numeric($k), ARRAY_FILTER_USE_KEY);

                // Run Middlewares pipeline
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middlewareResponse = $middleware->handle($request);
                    if ($middlewareResponse instanceof Response) {
                        return $middlewareResponse;
                    }
                }

                // Execute Controller Handler (check array first to instantiate controller class)
                $handler = $route['handler'];
                if (is_array($handler) && count($handler) === 2 && is_string($handler[0])) {
                    [$class, $method] = $handler;
                    $controller = new $class();
                    $result = call_user_func_array([$controller, $method], array_merge([$request], $params));
                } elseif (is_callable($handler)) {
                    $result = call_user_func_array($handler, array_merge([$request], $params));
                } else {
                    throw new \RuntimeException('Invalid route handler configuration.');
                }

                if ($result instanceof Response) {
                    return $result;
                }
                return Response::html((string)$result);
            }
        }

        // 404 Not Found
        return Response::html(View::render('errors.404', [], null), 404);
    }
}
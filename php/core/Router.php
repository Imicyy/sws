<?php

declare(strict_types=1);

class Router
{
    /** @var array<string, array<int, array{pattern:string,regex:string,paramNames:array<int,string>,handler:callable,middlewares:array<int,callable>}>> */
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler, array $middlewares = []): void
    {
        $method = strtoupper($method);
        [$regex, $paramNames] = $this->compilePattern($pattern);

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'regex' => $regex,
            'paramNames' => $paramNames,
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(string $method, string $uriPath): void
    {
        $method = strtoupper($method);
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (!preg_match($route['regex'], $uriPath, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['paramNames'] as $name) {
                $params[$name] = $matches[$name] ?? null;
            }

            foreach ($route['middlewares'] as $middleware) {
                $ok = $middleware($params);
                if ($ok === false) {
                    return;
                }
            }

            $handler = $route['handler'];
            $handler($params);
            return;
        }

        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Route not found',
            'path' => $uriPath,
            'method' => $method,
        ]);
    }

    /** @return array{0:string,1:array<int,string>} */
    private function compilePattern(string $pattern): array
    {
        $paramNames = [];
        $regex = preg_replace_callback('/\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}/', function (array $m) use (&$paramNames): string {
            $paramNames[] = $m[1];
            return '(?P<' . $m[1] . '>[^/]+)';
        }, $pattern);

        if ($regex === null) {
            $regex = $pattern;
        }

        return ['#^' . $regex . '$#', $paramNames];
    }
}

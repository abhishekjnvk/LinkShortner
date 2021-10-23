<?php

class AppRouter
{
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            $parameters = [];
            foreach ($route['params'] as $param) {
                $parameters[] = $matches[$param] ?? null;
            }

            $handler = $route['handler'];
            if (is_array($handler)) {
                [$className, $methodName] = $handler;
                $controller = new $className();
                $controller->{$methodName}(...$parameters);
                return;
            }

            $handler(...$parameters);
            return;
        }

        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
    }

    private function addRoute(string $method, string $pattern, callable|array $handler): void
    {
        $params = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $matches) use (&$params): string {
            $params[] = $matches[1];
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $pattern);

        $this->routes[$method][] = [
            'regex' => '#^' . $regex . '$#',
            'params' => $params,
            'handler' => $handler,
        ];
    }
}

<?php

class Router
{
    private array $routes = [];

    private function addRoute($method, $uri, $action)
    {
        $route = new Route(
            $method,
            $uri,
            $action
        );

        $this->routes[] = $route;

        return $route;
    }

    public function get($uri, $action)
    {
        return $this->addRoute(
            'GET',
            $uri,
            $action
        );
    }

    public function post($uri, $action)
    {
        return $this->addRoute(
            'POST',
            $uri,
            $action
        );
    }

 public function dispatch($uri, $method)
{
    $container = new Container();

    foreach ($this->routes as $route) {

        // Break both URLs into segments
        $routeParts = array_values(
            array_filter(
                explode('/', $route->getUri())
            )
        );

        $urlParts = array_values(
            array_filter(
                explode('/', $uri)
            )
        );

        // Skip if HTTP methods don't match
        if ($route->getMethod() !== strtoupper($method)) {
            continue;
        }

        // Skip if segment counts don't match
        if (count($routeParts) !== count($urlParts)) {
            continue;
        }

        $params = [];

        $matched = true;

        // Compare each segment
        for ($i = 0; $i < count($routeParts); $i++) {

            // Route parameter?
            if (
                str_starts_with($routeParts[$i], '{') &&
                str_ends_with($routeParts[$i], '}')
            ) {

                $name = trim($routeParts[$i], '{}');

                $params[$name] = $urlParts[$i];

                continue;
            }

            // Normal text must match
            if ($routeParts[$i] !== $urlParts[$i]) {

                $matched = false;

                break;
            }
        }

        if (!$matched) {
            continue;
        }

        $action = $route->getAction();

           // Create Request
        $request = new Request();

        $request->setRouteParams($params);


        foreach ($route->getMiddlewares() as $middleware) {

    switch ($middleware) {

        case "auth":

            $instance = $container->make(AuthMiddleware::class);

            break;

        default:

            throw new Exception(
                "Middleware '{$middleware}' not found."
            );

    }

    $instance->handle($request);

}

        // Closure Route
        if ($action instanceof Closure) {

           return call_user_func(
            $action,
            $request
        );
        }

        // Controller Route
        list($controller, $method) = explode('@', $action);

      $controller = $container->make($controller);

        return call_user_func_array(
            [$controller, $method],
            [$request]
        );  

    }

    http_response_code(404);

    echo "<h2>404 Page Not Found</h2>";
   }
}
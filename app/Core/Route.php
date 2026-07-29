<?php

class Route
{
    /**
     * HTTP Method.
     */
    protected string $method;

    /**
     * Route URI.
     */
    protected string $uri;

    /**
     * Controller action or callback.
     */
    protected $action;

    /**
     * Route middlewares.
     */
    protected array $middlewares = [];

    /**
     * Create a new Route instance.
     */
    public function __construct($method, $uri, $action)
    {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->action = $action;
    }

    /**
     * Assign middleware to the route.
     */
    public function middleware($middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * Get HTTP method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get URI.
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get action.
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get middlewares.
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}
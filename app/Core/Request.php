<?php

class Request
{

protected array $routeParams = [];
    /**
     * Get POST input
     */
    public function input($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET input
     */
    public function query($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if POST value exists
     */
    public function has($key)
    {
        return isset($_POST[$key]);
    }

    /**
     * Get uploaded file
     */
    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Request Method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Current URL
     */
    public function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Is POST Request?
     */
    public function isPost()
    {
        return $this->method() === "POST";
    }

    /**
     * Is GET Request?
     */
    public function isGet()
    {
        return $this->method() === "GET";
    }

    /**
 * Store route parameters
 */
public function setRouteParams(array $params)
{
    $this->routeParams = $params;
}

/**
 * Get route parameter
 */
public function route($key, $default = null)
{
    return $this->routeParams[$key] ?? $default;
}
}
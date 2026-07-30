<?php

class Request
{
    protected array $routeParams = [];

    public function all()
    {
        return $_POST;
    }

    public function input($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function only(array $keys)
    {
        $data = [];

        foreach ($keys as $key) {
            $data[$key] = $this->input($key);
        }

        return $data;
    }

    public function has($key)
    {
        return isset($_POST[$key]);
    }

    public function query($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function isPost()
    {
        return $this->method() === "POST";
    }

    public function isGet()
    {
        return $this->method() === "GET";
    }

    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
    }

    public function route($key, $default = null)
    {
        return $this->routeParams[$key] ?? $default;
    }
}
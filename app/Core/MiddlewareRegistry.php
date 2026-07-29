<?php

class MiddlewareRegistry
{
    /**
     * Registered middleware.
     */
    protected static $middlewares = [

        'auth' => AuthMiddleware::class,

        'role' => RoleMiddleware::class,

    ];

    /**
     * Get a middleware class.
     */
    public static function get($name)
    {
        return static::$middlewares[$name] ?? null;
    }

    /**
     * Register a middleware.
     */
    public static function register($name, $class)
    {
        static::$middlewares[$name] = $class;
    }
}
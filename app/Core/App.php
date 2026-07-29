<?php

class App
{
    public static function run()
    {
        $router = require "../routes/web.php";

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = "/nexora/public";

        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        if ($uri == "") {
            $uri = "/";
        }

        $router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
    }
}
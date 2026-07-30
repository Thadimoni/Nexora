<?php

$router = new Router();

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

$router->get("/", "HomeController@index");

$router->get("/student/{id}", "HomeController@show");

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

$router->get("/register", "AuthController@register");

$router->post("/register", "AuthController@store");

$router->get("/login", "AuthController@login");

$router->post("/login", "AuthController@authenticate");

$router->post("/logout", "AuthController@logout");

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

$router->get("/dashboard", function () {

    echo "Dashboard Loaded Successfully";

})->middleware("auth");

/*
|--------------------------------------------------------------------------
| Testing
|--------------------------------------------------------------------------
*/

$router->get("/test-container", function () {

    $container = new Container();

    $db1 = $container->make(Database::class);

    $db2 = $container->make(Database::class);

    dd($db1 === $db2);

});

return $router;
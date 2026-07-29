<?php

$router = new Router();

$router->get("/", "HomeController@index");

$router->get("/student/{id}", "HomeController@show");

$router->get("/test-container", function () {

    $container = new Container();

    $db1 = $container->make(Database::class);

    $db2 = $container->make(Database::class);

    dd($db1 === $db2);

});

$router->get("/dashboard", function () {

    echo "Dashboard Loaded Successfully";

})->middleware("auth");

$router->get("/login", "AuthController@login");

$router->post("/login", "AuthController@authenticate");

return $router;
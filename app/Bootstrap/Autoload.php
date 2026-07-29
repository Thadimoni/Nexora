<?php

spl_autoload_register(function ($class) {

   $folders = [

    "../app/Core/",
    "../app/Http/Controllers/",
    "../app/Models/",
    "../app/Config/",
    "../app/Http/Middleware/",
    "../app/Services/",
    "../app/Exceptions/",
    "../app/Repositories/",
    "../app/Traits/",
    

];

    foreach ($folders as $folder) {

        $file = $folder . $class . ".php";

        if (file_exists($file)) {

            require_once $file;

            return;
        }
    }

});
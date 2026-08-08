<?php

spl_autoload_register(function ($class) {

    $base = __DIR__ . "/../";

    $folders = [

        $base . "Core/",
        $base . "Http/Controllers/",
        $base . "Http/Middleware/",
        $base . "Models/",
        $base . "Config/",
        $base . "Services/",
        $base . "Exceptions/",
        $base . "Repositories/",
        $base . "Traits/",
        $base . "Console/",
        $base . "Console/Commands/",
        $base . "Database/",
    ];

    foreach ($folders as $folder) {

        $file = $folder . $class . ".php";

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

});
<?php

class View
{
    /**
     * Render a view.
     */
    public static function make(string $view, array $data = [])
{
    $path = "../resources/views/" . $view . ".php";

    if (!file_exists($path)) {

        throw new Exception(
            "View '{$view}' not found."
        );

    }

    extract($data);

    ob_start();

    include $path;

    $content = ob_get_clean();

    include "../resources/views/layouts/app.php";
}

}
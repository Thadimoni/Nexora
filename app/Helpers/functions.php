<?php

/**
 * Dump and die
 */
function dd($data)
{
    echo "<pre>";

    var_dump($data);

    echo "</pre>";

    die();
}

/**
 * Escape HTML
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect helper
 */
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

/**
 * Render a view.
 */
function view(string $view, array $data = [])
{
    return View::make($view, $data);
}
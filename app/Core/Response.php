<?php

class Response
{
    /**
     * Redirect to another page
     */
    public static function redirect($url)
    {
        header("Location: " . APP_URL . $url);

        exit;
    }

    /**
     * Return JSON response
     */
    public static function json($data)
    {
        header("Content-Type: application/json");

        echo json_encode($data);

        exit;
    }

    /**
     * Set HTTP status code
     */
    public static function status($code)
    {
        http_response_code($code);
    }

    /**
     * Output plain text
     */
    public static function send($content)
    {
        echo $content;
    }
}
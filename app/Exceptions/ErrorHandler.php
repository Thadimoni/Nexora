<?php

class ErrorHandler
{
    public static function register()
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }
        public static function handleException($exception)
        {
            self::log($exception);

            echo "<h1>500 - Internal Server Error</h1>";

            echo "<p><strong>Message:</strong> "
                . $exception->getMessage() . "</p>";

            echo "<p><strong>File:</strong> "
                . $exception->getFile() . "</p>";

            echo "<p><strong>Line:</strong> "
                . $exception->getLine() . "</p>";
        }

    public static function handleError($severity, $message, $file, $line)
    {
        throw new ErrorException(
            $message,
            0,
            $severity,
            $file,
            $line
        );
    }

    protected static function log($exception)
    {
        $log = "[" . date("Y-m-d H:i:s") . "]\n";
        $log .= "Message: " . $exception->getMessage() . "\n";
        $log .= "File: " . $exception->getFile() . "\n";
        $log .= "Line: " . $exception->getLine() . "\n";
        $log .= str_repeat("-", 60) . "\n";

        file_put_contents(
            "../storage/logs/error.log",
            $log,
            FILE_APPEND
        );
    }
}
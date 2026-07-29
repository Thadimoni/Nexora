<?php

class Security
{
    /**
     * Escape HTML Output (Prevents XSS)
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Hash Password
     */
    public function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify Password
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate Random Token
     */
    public function token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Generate Random String
     */
    public function random($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $string = '';

        for ($i = 0; $i < $length; $i++) {

            $string .= $characters[random_int(0, strlen($characters) - 1)];

        }

        return $string;
    }
}
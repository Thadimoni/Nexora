<?php

class Session
{
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

    public function all()
    {
        return $_SESSION;
    }

    public function flash($key, $message = null)
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return;
        }

        if (isset($_SESSION['_flash'][$key])) {
            $msg = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }

        return null;
    }

    public function login(array $user)
    {
    $this->set("user", $user);
    }

    public function user()
    {
    return $this->get("user");
    }

    public function check()
    {
    return $this->has("user");
    }

    public function logout()
    {
    $this->remove("user");
    }
}
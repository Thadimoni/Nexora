<?php

class Auth
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Login User
     */
    public function login(array $user)
    {
        $this->session->set("user", $user);
    }

    /**
     * Logout User
     */
    public function logout()
    {
        $this->session->remove("user");
    }

    /**
     * Is User Logged In?
     */
    public function check()
    {
        return $this->session->has("user");
    }

    /**
     * Get Logged User
     */
    public function user()
    {
        return $this->session->get("user");
    }

    /**
     * Get User ID
     */
    public function id()
    {
        $user = $this->user();

        return $user['id'] ?? null;
    }

    /**
     * Get User Role
     */
    public function role()
    {
        $user = $this->user();

        return $user['role'] ?? null;
    }

    /**
     * Check Role
     */
    public function is($role)
    {
        return $this->role() === $role;
    }
}
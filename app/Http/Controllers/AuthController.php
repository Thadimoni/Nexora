<?php

class AuthController extends BaseController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Show Login Page
     */
    public function login()
    {
        return $this->view("auth.login");
    }

    /**
     * Show Registration Page
     */
    public function register()
    {
        return $this->view("auth.register");
    }

    /**
     * Store New User
     */
    public function store(Request $request)
    {

    }

    /**
     * Login User
     */
    public function authenticate(Request $request)
    {

    }

    /**
     * Logout User
     */
    public function logout()
    {

    }
}
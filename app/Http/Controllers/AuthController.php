<?php

class AuthController
{
    /**
     * Show the login page.
     */
    public function login(Request $request)
    {
        return view("auth/login");
    }

    /**
     * Handle login.
     */
    public function authenticate(Request $request)
    {

    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {

    }
}
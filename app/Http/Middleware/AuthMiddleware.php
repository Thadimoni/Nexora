<?php

class AuthMiddleware extends Middleware
{
    public function handle(Request $request)
    {
        $auth = new Auth();

        dd($auth->check());
    }
}
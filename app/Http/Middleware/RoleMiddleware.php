<?php

class RoleMiddleware extends Middleware
{
    protected string $role;

    public function __construct($role = "")
    {
        $this->role = $role;
    }

    public function handle(Request $request)
    {
        $auth = new Auth();

        if (!$auth->check()) {

            (new Response())->redirect("/login");

        }

        if (!$auth->is($this->role)) {

            die("403 Forbidden");

        }

        return true;
    }
}
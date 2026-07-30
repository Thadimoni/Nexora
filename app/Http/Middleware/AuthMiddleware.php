<?php

class AuthMiddleware extends Middleware
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(Request $request)
    {
        if (!$this->session->check()) {

            $this->session->flash(
                "error",
                "Please login to continue."
            );

            header("Location: /login");
            exit;
        }
    }
}
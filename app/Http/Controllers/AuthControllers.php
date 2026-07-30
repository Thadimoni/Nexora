<?php

class AuthController extends BaseController
{
    private User $user;
    private Session $session;

    public function __construct(
        User $user,
        Session $session
    ) {
        $this->user = $user;
        $this->session = $session;
    }

    /**
     * Show Login Page
     */
    public function login()
    {
        return $this->view("auth.login");
    }

    /**
     * Show Register Page
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
    $data = $request->only([
        "name",
        "email",
        "password"
    ]);

    $validator = new Validator();

    $validator->validate($data, [
        "name" => "required|min:3|max:100",
        "email" => "required|email",
        "password" => "required|min:6"
    ]);

    if ($validator->fails()) {

        $this->session->flash(
            "error",
            $validator->first()
        );

        return $this->back();
    }

    if ($this->user->findByEmail($data["email"])) {

        $this->session->flash(
            "error",
            "Email already exists."
        );

         $this->back();
    }

    $this->user->create([
        "name" => $data["name"],
        "email" => $data["email"],
        "password" => password_hash(
            $data["password"],
            PASSWORD_DEFAULT
        ),
        "role" => "user"
    ]);

    $this->session->flash(
        "success",
        "Registration successful. Please login."
    );

     $this->redirect("/login");
}

    /**
     * Login User
     */
public function authenticate(Request $request)
{
    $data = $request->only([
        "email",
        "password"
    ]);

    $validator = new Validator();

    $validator->validate($data, [
        "email" => "required|email",
        "password" => "required"
    ]);

    if ($validator->fails()) {

        $this->session->flash(
            "error",
            $validator->first()
        );

        $this->back();
    }

    $user = $this->user->findByEmail(
        $data["email"]
    );

    if (!$user) {

        $this->session->flash(
            "error",
            "Invalid email or password."
        );

        $this->back();
    }

    if (!password_verify(
        $data["password"],
        $user["password"]
    )) {

        $this->session->flash(
            "error",
            "Invalid email or password."
        );

        $this->back();
    }

    unset($user["password"]);

    $this->session->login($user);

    $this->redirect("/dashboard");
}
    /**
     * Logout
     */
   public function logout()
{
    $this->session->logout();

    $this->session->destroy();

    $this->redirect("/login");
}
}
<?php

class User extends BaseModel
{
    protected string $table = "users";

    /**
     * Find user by email
     */
    public function findByEmail(string $email)
    {
        return $this->where("email", $email);
    }

    /**
     * Register new user
     */
    public function createUser(array $data)
    {
        $data["password"] = password_hash(
            $data["password"],
            PASSWORD_DEFAULT
        );

        return $this->create($data);
    }

    /**
     * Verify password
     */
    public function verifyPassword(
        string $password,
        string $hash
    )
    {
        return password_verify(
            $password,
            $hash
        );
    }

    /**
     * Check if admin
     */
    public function isAdmin(array $user)
    {
        return $user["role"] === "admin";
    }

    /**
     * Update remember token
     */
    public function updateRememberToken(
        int $id,
        string $token
    )
    {
        return $this->update(
            $id,
            [
                "remember_token" => $token
            ]
        );
    }
}
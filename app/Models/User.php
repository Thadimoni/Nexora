<?php

class User extends BaseModel
{
    protected string $table = "users";

    public function findByEmail($email)
    {
        return $this->where("email", $email);
    }
}
<?php

namespace Domain\Security\Request;

class LoginRequest
{
    public function __construct(private string $email, private string $plainPassword)
    {
    }

    public static function create(string $email, string $plainPassword): self
    {
        return new self($email, $plainPassword);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}

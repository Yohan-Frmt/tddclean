<?php

namespace Domain\Security\Entity;

class User
{
    public function getEmail(): string
    {
        return 'user@mail.com';
    }

    public function getUsername(): string
    {
        return 'username';
    }

    public function getPassword(): string
    {
        return 'password';
    }
}
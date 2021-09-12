<?php

namespace Domain\Security\Response;

use Domain\Security\Entity\User;

class LoginResponse
{

    public function __construct(
        private User $user,
        private string $plainPassword,
        private bool $passwordIdValid
    ) {
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isPasswordIdValid(): bool
    {
        return $this->passwordIdValid;
    }
}

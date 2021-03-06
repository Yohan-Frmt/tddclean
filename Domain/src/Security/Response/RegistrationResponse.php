<?php

namespace Domain\Security\Response;

use Domain\Security\Entity\User;

class RegistrationResponse
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

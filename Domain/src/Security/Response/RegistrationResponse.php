<?php

namespace Domain\Security\Response;

use Domain\Security\Entity\User;

class RegistrationResponse
{
    public function getUser(): User
    {
        return new User;
    }
}
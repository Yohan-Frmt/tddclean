<?php

namespace Domain\Tests\Adapter;

use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;

class UserRepository implements UserGateway
{
    public function isEmailUnique(?string $email): bool
    {
        return $email != "duplicate@mail.com";
    }

    public function isUsernameUnique(?string $username): bool
    {
        return $username != "duplicate";
    }

    public function getUserByEmail(string $email): ?User
    {
        // TODO: Implement getUserByEmail() method.
        return null;
    }

    public function register(User $user): void
    {
        // TODO: Implement register() method.
    }

    public function update(User $user): void
    {
        // TODO: Implement update() method.
    }
}

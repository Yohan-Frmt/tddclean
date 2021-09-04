<?php

namespace App\Infrastructure\Adapter\Repository;

use DateTimeImmutable;
use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\Uuid;

class UserRepository implements UserGateway
{
    public function isEmailUnique(?string $email): bool
    {
        // TODO: Implement isEmailUnique() method.
        return true;
    }

    public function isUsernameUnique(?string $username): bool
    {
        // TODO: Implement isUsernameUnique() method.
        return true;
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

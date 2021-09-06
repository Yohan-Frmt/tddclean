<?php

namespace Domain\Tests\Adapter;

use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\UuidV4;

use function password_hash;

use const PASSWORD_ARGON2I;

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
        if ($email !== 'user@mail.com') {
            return null;
        }
        return new User(
            id: UuidV4::v4(),
            email: 'user@mail.com',
            username: 'username',
            password: password_hash(
                password: 'password',
                algo: PASSWORD_ARGON2I
            )
        );
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

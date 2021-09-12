<?php

namespace App\Infrastructure\Test\Adapter;

use DateTimeImmutable;
use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\Uuid;

class UserRepository implements UserGateway
{
    public function getUserByEmail(string $email): ?User
    {
        return $email !== "duplicate@mail.com" ? null : new User(
            id: Uuid::v4(),
            email: $email,
            username: "username",
            password: password_hash(password: 'password', algo: PASSWORD_ARGON2I),
            passwordResetToken: 'bb4b5730-6057-4fa1-a27b-692b9ba8c14a',
            passwordResetRequestedAt: new DateTimeImmutable(),
            lastLogin: new DateTimeImmutable()
        );
    }

    public function isEmailUnique(?string $email): bool
    {
        return $email != "duplicate@mail.com";
    }

    public function isUsernameUnique(?string $username): bool
    {
        return $username != "duplicate";
    }

    public function register(User $user): void
    {
    }

    public function update(User $user): void
    {
    }
}

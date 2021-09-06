<?php

namespace Domain\Security\Gateway;

use Domain\Security\Entity\User;

interface UserGateway
{
    public function getUserByEmail(string $email): ?User;
    public function isEmailUnique(?string $email): bool;
    public function isUsernameUnique(?string $username): bool;
    public function register(User $user): void;
    public function update(User $user): void;
}

<?php

namespace App\Infrastructure\Security\Provider;

use App\Infrastructure\Security\User;
use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    private function getUserByEmail(string $email): User
    {
        $user = $this->userGateway->getUserByEmail($email);
        return $user === null ? throw new UserNotFoundException() : new User($user);
    }

    public function refreshUser(UserInterface $user): User
    {
        return $this->getUserByEmail($user->getUserIdentifier());
    }


    public function loadUserByIdentifier(string $identifier): User
    {
        return $this->getUserByEmail($identifier);
    }

    public function loadUserByUsername(string $username)
    {
        // TODO: Implement loadUserByUsername() method.
    }


    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}

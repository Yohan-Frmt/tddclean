<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{


    public function __construct(private \Domain\Security\Entity\User $user)
    {
    }

    public function getRoles(): array
    {
        return ['ROLE_USER',];
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getEmail();
    }

    public function getUsername(): string
    {
        return $this->user->getUsername();
    }
}

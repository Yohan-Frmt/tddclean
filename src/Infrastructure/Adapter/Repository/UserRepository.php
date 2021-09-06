<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;

class UserRepository extends ServiceEntityRepository implements UserGateway
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, \App\Infrastructure\Doctrine\Entity\User::class);
    }

    public function isEmailUnique(?string $email): bool
    {
        return $this->count(['email' => $email,]) === 0;
    }

    public function isUsernameUnique(?string $username): bool
    {
        return $this->count(['username' => $username,]) === 0;
    }

    public function getUserByEmail(string $email): ?User
    {
        // TODO: Implement getUserByEmail() method.
        return null;
    }

    public function register(User $user): void
    {
        $doctrineUser = new \App\Infrastructure\Doctrine\Entity\User();
        $doctrineUser->setEmail($user->getEmail());
        $doctrineUser->setUsername($user->getUsername());
        $doctrineUser->setPassword($user->getPassword());
        $this->_em->persist($doctrineUser);
        $this->_em->flush();
    }

    public function update(User $user): void
    {
        // TODO: Implement update() method.
    }
}

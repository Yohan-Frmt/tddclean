<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;

use function is_null;

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
        $user = $this->findOneBy(['email' => $email]);
        if (is_null($user)) {
            return null;
        }

        return new User(
            id: $user->getId(),
            email: $user->getEmail(),
            username: $user->getUsername(),
            password: $user->getPassword(),
            passwordResetToken: $user->getPasswordResetToken(),
            passwordResetRequestedAt: $user->getPasswordResetRequestedAt(),
            lastLogin: $user->getLastLogin()
        );
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

<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Infrastructure\Doctrine\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("username");
        $user->setEmail("user@mail.com");
        $user->setPassword(password_hash("password", PASSWORD_ARGON2I));
        $manager->persist($user);
        $manager->flush();
    }
}

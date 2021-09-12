<?php

namespace App\Tests\Integration;

use App\Infrastructure\Doctrine\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class UserTest extends TestCase
{
    private User $user;

    public function testUserFields(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user);

        $this->assertNotEmpty($this->user->getId());
        $this->assertInstanceOf(UuidV4::class, $this->user->getId());

        $this->assertNotEmpty($this->user->getEmail());
        $this->assertIsString($this->user->getEmail());
        $this->assertEquals('rybard@mail.com', $this->user->getEmail());

        $this->assertNotEmpty($this->user->getUsername());
        $this->assertIsString($this->user->getUsername());
        $this->assertEquals('rybard', $this->user->getUsername());

        $this->assertNotEmpty($this->user->getPassword());
        $this->assertIsString($this->user->getPassword());
        $this->assertEquals('password', $this->user->getPassword());

        $this->assertNotEmpty($this->user->getFirstName());
        $this->assertIsString($this->user->getFirstName());
        $this->assertEquals('John', $this->user->getFirstName());

        $this->assertNotEmpty($this->user->getLastName());
        $this->assertIsString($this->user->getLastName());
        $this->assertEquals('Doe', $this->user->getLastName());

        $this->assertNotEmpty($this->user->getRoles());
        $this->assertIsArray($this->user->getRoles());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());

        $this->assertNotEmpty($this->user->getPasswordResetToken());
        $this->assertIsString($this->user->getPasswordResetToken());
        $this->assertEquals('token_reset', $this->user->getPasswordResetToken());

        $this->assertNotNull($this->user->getPasswordResetRequestedAt());
        $this->assertEquals(
            DateTimeImmutable::createFromMutable(new \DateTime('today')),
            $this->user->getPasswordResetRequestedAt()
        );

        $this->assertNotNull($this->user->getLastLogin());
        $this->assertEquals(
            DateTimeImmutable::createFromMutable(new \DateTime('today')),
            $this->user->getLastLogin()
        );
    }

    protected function setUp(): void
    {
        $this->user = User::create(
            email: 'rybard@mail.com',
            username: 'rybard',
            password: 'password',
            firstname: 'John',
            lastname: 'Doe',
            role: ['ROLE_ADMIN'],
            passwordResetToken: 'token_reset',
            passwordResetRequestedAt: DateTimeImmutable::createFromMutable(new \DateTime('today')),
            lastLogin: DateTimeImmutable::createFromMutable(new \DateTime('today'))
        );
    }
}

<?php

namespace Domain\Security\Entity;

use DateTimeInterface;
use Domain\Security\Request\RegistrationRequest;

use Symfony\Component\Uid\UuidV4;
use function password_hash;

use const PASSWORD_ARGON2I;

class User
{
    public function __construct(
        private UuidV4 $id,
        private string $email,
        private string $username,
        private string $password,
        private ?string $passwordResetToken = null,
        private ?DateTimeInterface $passwordResetRequestedAt = null,
        private ?DateTimeInterface $lastLogin = null
    ) {
    }

    public static function create(RegistrationRequest $request): self
    {
        return new self(
            id: UuidV4::v4(),
            email: $request->getEmail(),
            username: $request->getUsername(),
            password: password_hash($request->getPlainPassword(), PASSWORD_ARGON2I)
        );
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function getPasswordResetRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordResetRequestedAt;
    }

    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}

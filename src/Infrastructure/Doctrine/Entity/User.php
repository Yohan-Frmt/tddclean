<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Infrastructure\Adapter\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: "`user`")]
#[UniqueEntity(fields: ['email', 'username', 'id'])]
class User implements Serializable
{

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    #[Id]
    #[Required]
    #[Column(type: 'uuid')]
    #[GeneratedValue(strategy: 'UUID')]
    private UuidV4 $id;

    #[Required]
    #[NotBlank]
    #[Email]
    #[Column(type: Types::STRING)]
    private string $email;

    #[Required]
    #[NotBlank]
    #[Column(type: Types::STRING)]
    private string $username;

    #[Required]
    #[NotBlank]
    #[Column(type: Types::STRING)]
    private string $firstName = '';

    #[Required]
    #[NotBlank]
    #[Column(type: Types::STRING)]
    private string $lastName = '';

    #[Column(type: Types::JSON)]
    private array $roles = [];

    #[Required]
    #[Column(type: Types::STRING)]
    private string $password;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $passwordResetToken = null;

    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $passwordResetRequestedAt = null;

    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastLogin = null;

    public function getId(): string
    {
        return $this->id->jsonSerialize();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): self
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }

    public function getPasswordResetRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordResetRequestedAt;
    }

    public function setPasswordResetRequestedAt(?DateTimeInterface $passwordResetRequestedAt): self
    {
        $this->passwordResetRequestedAt = $passwordResetRequestedAt;
        return $this;
    }

    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles,
            $this->passwordResetToken,
            $this->passwordResetRequestedAt,
            $this->lastLogin,
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles,
            $this->passwordResetToken,
            $this->passwordResetRequestedAt,
            $this->lastLogin,
            ) = unserialize($data, ["allowed_class" => false,]);
    }
}

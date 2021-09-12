<?php

namespace App\Infrastructure\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Uid\UuidV4;
use Doctrine\ORM\Mapping\GeneratedValue;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Infrastructure\Adapter\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: "`user`")]
#[UniqueEntity(fields: ['email', 'username', 'id'])]
class User
{

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

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $firstName = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $lastName = null;

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

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function create(
        string $email,
        string $username,
        string $password,
        string $firstname = null,
        string $lastname = null,
        array $role = [],
        string $passwordResetToken = null,
        DateTimeInterface $passwordResetRequestedAt = null,
        DateTimeInterface $lastLogin = null,
    ): User {
        $self = new self();
        $self->setEmail($email);
        $self->setUsername($username);
        $self->setPassword($password);
        $self->setFirstName($firstname);
        $self->setLastName($lastname);
        $self->setRoles($role);
        $self->setPasswordResetToken($passwordResetToken);
        $self->setPasswordResetRequestedAt($passwordResetRequestedAt);
        $self->setLastLogin($lastLogin);
        return $self;
    }

    public function getId(): UuidV4
    {
        return $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
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
}

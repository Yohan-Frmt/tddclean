<?php

namespace Domain\Security\Request;

use Domain\Security\Assert\Assert;
use Domain\Security\Gateway\UserGateway;

class RegistrationRequest
{

    public function __construct(private string $email, private string $username, private string $plainPassword)
    {
    }

    public static function create(string $email, string $username, string $plainPassword): self
    {
        return new self($email, $username, $plainPassword);
    }

    public function validate(UserGateway $userGateway): void
    {
        Assert::notBlank(value: $this->email);
        Assert::email(value: $this->email);
        Assert::uniqueEmail(email: $this->email, userGateway: $userGateway);
        Assert::notBlank(value: $this->username);
        Assert::uniqueUsername(username: $this->username, userGateway: $userGateway);
        Assert::notBlank(value: $this->plainPassword);
        Assert::minLength(value: $this->plainPassword, minLength: 8);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}

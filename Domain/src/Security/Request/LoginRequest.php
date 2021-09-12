<?php

namespace Domain\Security\Request;

use Domain\Security\Assert\Assert;

class LoginRequest
{
    public function __construct(private string $email, private string $plainPassword)
    {
    }

    public static function create(string $email, string $plainPassword): self
    {
        return new self($email, $plainPassword);
    }

    public function validate(): void
    {
        Assert::notBlank(value: $this->email, message: 'This value should not be blank.');
        Assert::email(value: $this->email, message: 'Value was expected to be a valid e-mail address.');
        Assert::notBlank(value: $this->plainPassword, message: 'This value should not be blank.');
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}

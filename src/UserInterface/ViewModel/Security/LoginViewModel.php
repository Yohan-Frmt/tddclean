<?php

namespace App\UserInterface\ViewModel\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginViewModel
{

    public static function create(AuthenticationUtils $utils): self
    {
        return new self($utils->getLastUsername(), $utils->getLastAuthenticationError());
    }

    public function __construct(private string $lastUsername, private ?AuthenticationException $exception)
    {
    }

    public function getLastUsername(): string
    {
        return $this->lastUsername;
    }

    public function getException(): AuthenticationException
    {
        return $this->exception;
    }
}

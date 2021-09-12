<?php

namespace Domain\Security\UseCase;

use Domain\Security\Gateway\UserGateway;
use Domain\Security\Presenter\LoginPresenterInterface;
use Domain\Security\Request\LoginRequest;
use Domain\Security\Response\LoginResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

use function is_null;
use function password_verify;

class Login
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function execute(LoginRequest $request, LoginPresenterInterface $presenter): void
    {
        $request->validate();
        $user = $this->userGateway->getUserByEmail($request->getEmail());
        if (is_null($user)) {
            throw new UserNotFoundException();
        }
        if (
            !password_verify(
                password: $request->getPlainPassword(),
                hash: $user->getPassword()
            )
        ) {
            throw new AuthenticationException('Wrong Credentials!');
        }
        $presenter->present(
            response: new LoginResponse(
                user: $user,
                plainPassword: $request->getPlainPassword(),
                passwordIdValid: true
            )
        );
    }
}

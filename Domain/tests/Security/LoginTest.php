<?php

namespace Domain\Tests\Security;

use Domain\Security\Entity\User;
use Domain\Security\Presenter\LoginPresenter;
use Domain\Security\Request\LoginRequest;
use Domain\Security\Response\LoginResponse;
use Domain\Security\UseCase\Login;
use Domain\Tests\Adapter\UserRepository;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function test(): void
    {
        $request = LoginRequest::create(
            email: 'user@mail.com',
            plainPassword: 'password'
        );

        $presenter = new class () implements LoginPresenter        {
            public LoginResponse $response;

            public function present(LoginResponse $response): void
            {
                $this->response = $response;
            }
        };

        $userGateway = new UserRepository();

        $useCase = new Login($userGateway);

        $useCase->execute($request, $presenter);

        $this->assertInstanceOf(
            expected: LoginResponse::class,
            actual: $presenter->response
        );

        $this->assertInstanceOf(
            expected: User::class,
            actual: $presenter->response->getUser()
        );
    }
}

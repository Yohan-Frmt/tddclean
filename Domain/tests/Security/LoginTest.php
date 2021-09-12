<?php

namespace Domain\Tests\Security;

use Assert\AssertionFailedException;
use Domain\Security\Entity\User;
use Domain\Security\Presenter\LoginPresenterInterface;
use Domain\Security\Request\LoginRequest;
use Domain\Security\Response\LoginResponse;
use Domain\Security\UseCase\Login;
use Domain\Tests\Adapter\UserRepository;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class LoginTest extends TestCase
{

    private Login $useCase;
    private LoginPresenterInterface $presenter;

    protected function setUp(): void
    {
        $this->presenter = new class () implements LoginPresenterInterface {
            public LoginResponse $response;

            public function present(LoginResponse $response): void
            {
                $this->response = $response;
            }
        };
        $userGateway = new UserRepository();

        $this->useCase = new Login($userGateway);
    }

    public function testShouldBeSuccessful(): void
    {
        $request = LoginRequest::create(
            email: 'user@mail.com',
            plainPassword: 'password'
        );

        $presenter = new class () implements LoginPresenterInterface {
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

        $this->assertTrue(
            condition: $presenter->response->isPasswordIdValid(),
        );
    }

    public function testShouldRaiseUserNotFoundException(): void
    {
        $request = LoginRequest::create(
            email: 'bad@bad.com',
            plainPassword: 'password'
        );

        $this->expectException(UserNotFoundException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function testShouldRaiseInvalidPasswordException(): void
    {
        $request = LoginRequest::create(
            email: 'user@mail.com',
            plainPassword: 'badpassword'
        );

        $this->expectException(AuthenticationException::class);
        $this->useCase->execute($request, $this->presenter);
    }
    /**
     * @dataProvider provideBadData
     */
    public function testShouldRaiseAssertionFailedException(string $email, string $plainPassword): void
    {
        $request = LoginRequest::create(
            email: $email,
            plainPassword: $plainPassword
        );
        $this->expectException(AssertionFailedException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function provideBadData(): Generator
    {
        yield ['', 'password',];
        yield ['email', 'password',];
        yield ['user@mail.com', '',];
    }
}

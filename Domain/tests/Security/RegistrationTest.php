<?php

namespace Domain\Tests\Security;

use Assert\AssertionFailedException;
use Domain\Security\Entity\User;
use Domain\Security\Presenter\RegistrationPresenter;
use Domain\Security\Request\RegistrationRequest;
use Domain\Security\Response\RegistrationResponse;
use Domain\Security\UseCase\Registration;
use Domain\Tests\Adapter\UserRepository;
use Generator;
use PHPUnit\Framework\TestCase;

use function password_verify;

class RegistrationTest extends TestCase
{
    private Registration $useCase;
    private RegistrationPresenter $presenter;

    protected function setUp(): void
    {
        $this->presenter = new class () implements RegistrationPresenter
        {
            public RegistrationResponse $response;

            public function present(RegistrationResponse $response): void
            {
                $this->response = $response;
            }
        };
        $userGateway = new UserRepository();

        $this->useCase = new Registration($userGateway);
    }


    public function testShouldBeSuccessful(): void
    {
        $request = RegistrationRequest::create(
            email: 'user@mail.com',
            username: 'username',
            plainPassword: 'password'
        );

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(
            expected: RegistrationResponse::class,
            actual: $this->presenter->response
        );

        $this->assertInstanceOf(
            expected: User::class,
            actual: $this->presenter->response->getUser()
        );

        $this->assertEquals(
            expected: 'user@mail.com',
            actual: $this->presenter->response->getUser()->getEmail()
        );

        $this->assertEquals(
            expected: 'username',
            actual: $this->presenter->response->getUser()->getUsername()
        );

        $this->assertTrue(
            condition: password_verify(
                password: 'password',
                hash: $this->presenter->response->getUser()->getPassword()
            )
        );
    }

    /**
     * @dataProvider provideBadData
     */
    public function testShouldFailed(string $email, string $username, string $plainPassword): void
    {
        $request = RegistrationRequest::create(
            email: $email,
            username: $username,
            plainPassword: $plainPassword
        );
        $this->expectException(AssertionFailedException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function provideBadData(): Generator
    {
        yield ['', 'username', 'password',];
        yield ['email', 'username', 'password',];
        yield ['user@mail.com', '', 'password',];
        yield ['user@mail.com', 'username', '',];
        yield ['user@mail.com', 'username', 'bad',];
        yield ['duplicate@mail.com', 'username', 'password',];
        yield ['user@mail.com', 'duplicate', 'password',];
    }
}

<?php

namespace Domain\Tests\Security;

use Domain\Security\Entity\User;
use Domain\Security\Presenter\RegistrationPresenter;
use Domain\Security\Request\RegistrationRequest;
use Domain\Security\Response\RegistrationResponse;
use Domain\Security\UseCase\Registration;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    public function test(): void
    {
        $request = new RegistrationRequest();

        $presenter = new class() implements RegistrationPresenter        {
            public RegistrationResponse $response;

            public function present(RegistrationResponse $response): void
            {
                $this->response = $response;
            }
        };

        $useCase = new Registration();

        $useCase->execute($request, $presenter);

        $this->assertInstanceOf(expected: RegistrationResponse::class, actual: $presenter->response);

        $this->assertInstanceOf(expected: User::class, actual: $presenter->response->getUser());

        $this->assertEquals(expected: 'user@mail.com', actual: $presenter->response->getUser()->getEmail());

        $this->assertEquals(expected: 'username', actual: $presenter->response->getUser()->getUsername());

        $this->assertEquals(expected: 'password', actual: $presenter->response->getUser()->getPassword());

    }
}
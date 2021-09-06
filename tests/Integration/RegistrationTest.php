<?php

namespace App\Tests\Integration;

use App\Infrastructure\Test\IntegrationTestCase;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends IntegrationTestCase
{
    public function testShouldBeSuccessful(): void
    {
        $client = static::createClient();
        $generator = $client->getContainer()->get("router");
        $crawler = $client->request(
            method: Request::METHOD_GET,
            uri: $generator->generate(name: 'register')
        );
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter(selector: 'form')->form([
            'registration[email]'                 => 'user@mail.com',
            'registration[username]'              => 'username',
            'registration[plainPassword][first]'  => 'password',
            'registration[plainPassword][second]' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains(
            selector: '.alert.alert-success',
            text: 'Account successfully created'
        );
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testShouldFailed(
        string $email,
        string $username,
        array $plainPassword,
        string $error
    ): void {
        $client = static::createClient();
        $generator = $client->getContainer()->get("router");
        $crawler = $client->request(
            method: Request::METHOD_GET,
            uri: $generator->generate(name: 'register')
        );
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter(selector: 'form')->form([
            'registration[email]'                 => $email,
            'registration[username]'              => $username,
            'registration[plainPassword][first]'  => $plainPassword['first'],
            'registration[plainPassword][second]' => $plainPassword['second'],
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
        $this->assertSelectorTextContains(
            selector: 'html',
            text: $error
        );
    }

    public function provideFailedData(): Generator
    {
        yield [
            'email'         => '',
            'username'      => 'username',
            'plainPassword' => [
                'first'  => 'password',
                'second' => 'password'
            ],
            'error'         => 'This value should not be blank.'
        ];
        yield [
            'email'         => 'user@mail.com',
            'username'      => '',
            'plainPassword' => [
                'first'  => 'password',
                'second' => 'password'
            ],
            'error'         => 'This value should not be blank.'
        ];
        yield [
            'email'         => 'user@mail.com',
            'username'      => 'username',
            'plainPassword' => [
                'first'  => '123',
                'second' => '123'
            ],
            'error'         => 'Password is too short. It should have 8 characters or more.'
        ];
        yield [
            'email'         => 'user@mail.com',
            'username'      => 'username',
            'plainPassword' => [
                'first'  => 'password',
                'second' => ''
            ],
            'error'         => 'Confirmation does not match password!'
        ];
    }
}

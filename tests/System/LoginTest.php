<?php

namespace App\Tests\System;

use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testShouldBeSuccessful(): void
    {
        $client = static::createClient();
        $generator = $client->getContainer()->get("router");
        $crawler = $client->request(
            method: Request::METHOD_GET,
            uri: $generator->generate(name: 'security_login')
        );
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter(selector: 'form')->form([
            'username' => 'user@mail.com',
            'password' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains(
            selector: '.alert.alert-success',
            text: 'Welcome Back!'
        );
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testShouldFailed(
        string $username,
        string $password,
        string $error
    ): void {
        $client = static::createClient();
        $generator = $client->getContainer()->get("router");
        $crawler = $client->request(
            method: Request::METHOD_GET,
            uri: $generator->generate(name: 'security_login')
        );
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter(selector: 'form')->form([
            'username' => $username,
            'password' => $password,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
        $this->assertSelectorTextContains(
            selector: '.alert.alert-danger',
            text: $error
        );
    }

    public function provideFailedData(): Generator
    {
        yield [
            'username' => '',
            'password' => 'password',
            'error'    => 'This value should not be blank.',
        ];
        yield [
            'username' => 'user@mail.com',
            'password' => '',
            'error'    => 'This value should not be blank.',
        ];
        yield [
            'username' => 'bad@bas',
            'password' => 'password',
            'error'    => 'Value was expected to be a valid e-mail address.',
        ];
        yield [
            'username' => 'user@mail.com',
            'password' => 'badpassword',
            'error'    => 'Wrong Credentials!',
        ];
    }
}

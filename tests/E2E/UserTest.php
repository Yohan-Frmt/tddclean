<?php

namespace App\Tests\E2E;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Panther\PantherTestCase;

class UserTest extends PantherTestCase
{
    public function test(): void
    {
        $client = self::createPantherClient();

        $crawler = $client->request(method: Request::METHOD_GET, uri: '/register');

        $crawler->filter(selector: 'form')->form([
            'registration[email]'                 => 'user@mail.com',
            'registration[username]'              => 'username',
            'registration[plainPassword][first]'  => 'password',
            'registration[plainPassword][second]' => 'password',
        ]);
//        $link = $crawler->selectButton('Register');
        $client->executeScript(
            script: 'document.querySelector("button").click()'
        );
        $this->assertSelectorTextContains(
            selector: '.alert.alert-success',
            text: 'Account successfully created'
        );
    }
}

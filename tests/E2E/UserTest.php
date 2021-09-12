<?php

namespace App\Tests\E2E;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Panther\PantherTestCase;

class UserTest extends PantherTestCase
{
    public function testIfEndToEndScenarioIsSuccessful(): void
    {
        $client = self::createPantherClient();

        /**
         * ===================START REGISTRATION===================
         */
        $crawler = $client->request(method: Request::METHOD_GET, uri: '/register');

        $crawler->filter(selector: 'form')->form([
            'registration[email]'                 => 'e2e_user@mail.com',
            'registration[username]'              => 'e2e_username',
            'registration[plainPassword][first]'  => 'password',
            'registration[plainPassword][second]' => 'password',
        ]);
  
        $client->executeScript(
            script: 'document.querySelector("button").click()'
        );
        $this->assertSelectorTextContains(
            selector: '.alert.alert-success',
            text: 'Account successfully created'
        );
        /**
         * ===================END REGISTRATION===================
         */


        /**
         * ===================START LOGIN===================
         */
        $crawler = $client->request(method: Request::METHOD_GET, uri: '/login');

        $crawler->filter(selector: 'form')->form([
            'username' => 'e2e_user@mail.com',
            'password' => 'password',
        ]);

        $client->executeScript(
            script: 'document.querySelector("button").click()'
        );

        $this->assertSelectorTextContains(
            selector: '.alert.alert-success',
            text: 'Welcome Back!'
        );
        /**
         * ===================END LOGIN===================
         */
    }
}

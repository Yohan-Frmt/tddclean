<?php

namespace App\Infrastructure\Test;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function array_merge;

class IntegrationTestCase extends WebTestCase
{
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(
            options: array_merge($options, ['environment' => 'test']),
            server: $server
        );
    }
}

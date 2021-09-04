<?php

namespace Domain\Security\Assert;

use Assert\Assertion;
use Domain\Security\Exception\NonUniqueEntry;
use Domain\Security\Gateway\UserGateway;

class Assert extends Assertion
{
    public const NON_UNIQUE_EMAIL = 300;
    public const NON_UNIQUE_USERNAME = 301;

    public static function uniqueEmail(string $email, UserGateway $user_gateway): void
    {
        if (!$user_gateway->isEmailUnique($email)) {
            throw new NonUniqueEntry("Email is already used!", self::NON_UNIQUE_EMAIL);
        }
    }

    public static function uniqueUsername(string $username, UserGateway $user_gateway): void
    {
        if (!$user_gateway->isUsernameUnique($username)) {
            throw new NonUniqueEntry("Username is already used!", self::NON_UNIQUE_USERNAME);
        }
    }
}
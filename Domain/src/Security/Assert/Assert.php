<?php

namespace Domain\Security\Assert;

use Assert\Assertion;
use Domain\Security\Exception\NonUniqueEntry;
use Domain\Security\Gateway\UserGateway;

class Assert extends Assertion
{
    public const NON_UNIQUE_EMAIL = 300;
    public const NON_UNIQUE_USERNAME = 301;

    public static function uniqueEmail(string $email, UserGateway $userGateway): void
    {
        if (!$userGateway->isEmailUnique($email)) {
            throw new NonUniqueEntry(
                message: "Email is already used!",
                code: self::NON_UNIQUE_EMAIL
            );
        }
    }

    public static function uniqueUsername(string $username, UserGateway $userGateway): void
    {
        if (!$userGateway->isUsernameUnique($username)) {
            throw new NonUniqueEntry(
                message: "Username is already used!",
                code: self::NON_UNIQUE_USERNAME
            );
        }
    }
}

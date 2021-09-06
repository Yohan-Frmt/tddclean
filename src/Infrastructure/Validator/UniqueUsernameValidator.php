<?php

namespace App\Infrastructure\Validator;

use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$this->userGateway->isUsernameUnique(username: $value)) {
            $this->context->buildViolation(message: $constraint->error)->addViolation();
        }
    }
}

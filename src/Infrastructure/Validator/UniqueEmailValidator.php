<?php

namespace App\Infrastructure\Validator;

use Domain\Security\Gateway\UserGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$this->userGateway->isEmailUnique(email: $value)) {
            $this->context->buildViolation(message: $constraint->error)->addViolation();
        }
    }
}

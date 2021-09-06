<?php

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

class UniqueEmail extends Constraint
{
    public string $error = 'Oops! This email is already used!';
}

<?php

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

class UniqueUsername extends Constraint
{
    public string $error = 'Oops! This username is already used!';
}

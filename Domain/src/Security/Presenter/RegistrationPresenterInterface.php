<?php

namespace Domain\Security\Presenter;

use Domain\Security\Response\RegistrationResponse;

interface RegistrationPresenterInterface
{
    public function present(RegistrationResponse $response): void;
}

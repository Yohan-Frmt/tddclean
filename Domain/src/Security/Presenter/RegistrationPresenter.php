<?php

namespace Domain\Security\Presenter;

use Domain\Security\Response\RegistrationResponse;

interface RegistrationPresenter
{
    public function present(RegistrationResponse $response): void;
}

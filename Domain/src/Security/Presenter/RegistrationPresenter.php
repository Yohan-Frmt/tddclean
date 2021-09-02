<?php

namespace Domain\Security\Presenter;

use Domain\Security\Response\RegistrationResponse;

interface RegistrationPresenter
{
    /**
    * @param RegistrationResponse $response
    */
    public function present(RegistrationResponse $response): void;
}
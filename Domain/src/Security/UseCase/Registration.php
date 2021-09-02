<?php

namespace Domain\Security\UseCase;

use Domain\Security\Request\RegistrationRequest;
use Domain\Security\Response\RegistrationResponse;
use Domain\Security\Presenter\RegistrationPresenter;


class Registration
{
    /**
    * @param RegistrationRequest $request
    * @param RegistrationPresenter $presenter
    */
    public function execute(RegistrationRequest $request, RegistrationPresenter $presenter)
    {
        $presenter->present(new RegistrationResponse());
    }
}
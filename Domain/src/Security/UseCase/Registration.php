<?php

namespace Domain\Security\UseCase;

use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;
use Domain\Security\Request\RegistrationRequest;
use Domain\Security\Response\RegistrationResponse;
use Domain\Security\Presenter\RegistrationPresenterInterface;

class Registration
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function execute(RegistrationRequest $request, RegistrationPresenterInterface $presenter): void
    {
        $request->validate($this->userGateway);
        $user = User::create($request);
        $this->userGateway->register($user);
        $presenter->present(new RegistrationResponse($user));
    }
}

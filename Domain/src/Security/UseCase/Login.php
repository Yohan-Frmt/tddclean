<?php

namespace Domain\Security\UseCase;

use Domain\Security\Entity\User;
use Domain\Security\Gateway\UserGateway;
use Domain\Security\Presenter\LoginPresenter;
use Domain\Security\Request\LoginRequest;
use Domain\Security\Response\LoginResponse;
use Symfony\Component\Uid\UuidV4;

use function password_hash;

class Login
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function execute(LoginRequest $request, LoginPresenter $presenter)
    {
        $user = $this->userGateway->getUserByEmail($request->getEmail());
        $presenter->present(
            response: new LoginResponse($user)
        );
    }
}

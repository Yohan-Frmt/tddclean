<?php

namespace Domain\Security\Presenter;

use Domain\Security\Response\LoginResponse;

interface LoginPresenterInterface
{
    /**
    * @param LoginResponse $response
    */
    public function present(LoginResponse $response): void;
}

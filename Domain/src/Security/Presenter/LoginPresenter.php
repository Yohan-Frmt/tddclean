<?php

namespace Domain\Security\Presenter;

use Domain\Security\Response\LoginResponse;

interface LoginPresenter
{
    /**
    * @param LoginResponse $response
    */
    public function present(LoginResponse $response): void;
}

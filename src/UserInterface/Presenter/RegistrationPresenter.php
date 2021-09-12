<?php

namespace App\UserInterface\Presenter;

use App\UserInterface\ViewModel\Security\RegistrationViewModel;
use Domain\Security\Presenter\RegistrationPresenterInterface;
use Domain\Security\Response\RegistrationResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RegistrationPresenter implements RegistrationPresenterInterface
{
    private RegistrationViewModel $viewModel;

    public function __construct(private FlashBagInterface $flashBag, private UserProviderInterface $userProvider)
    {
    }

    public function present(RegistrationResponse $response): void
    {
        $this->viewModel = new RegistrationViewModel(
            user: $this->userProvider->loadUserByIdentifier(
                identifier: $response->getUser()->getEmail()
            )
        );
    }

    public function getViewModel(): RegistrationViewModel
    {
        return $this->viewModel;
    }
}

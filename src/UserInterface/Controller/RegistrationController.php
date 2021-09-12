<?php

namespace App\UserInterface\Controller;

use App\Infrastructure\Security\Authenticator\WebAuthenticator;
use App\UserInterface\Form\RegistrationType;
use App\UserInterface\Presenter\RegistrationPresenter;
use Couchbase\Authenticator;
use Domain\Security\Request\RegistrationRequest;
use Domain\Security\UseCase\Registration;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManager;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Twig\Environment;

class RegistrationController
{
    public function __construct(
        private FormFactoryInterface $factory,
        private Environment $twig,
        private UrlGeneratorInterface $generator,
        private FlashBagInterface $flashBag,
        private UserAuthenticatorInterface $userAuthenticator,
        private WebAuthenticator $webAuthenticator,
    ) {
    }

    public function __invoke(Request $request, Registration $registration, RegistrationPresenter $presenter): Response
    {
        $form = $this->factory
            ->create(type: RegistrationType::class)
            ->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationRequest = RegistrationRequest::create(
                email: $form->getData()->getEmail(),
                username: $form->getData()->getUsername(),
                plainPassword: $form->getData()->getPlainPassword()
            );
            $registration->execute(
                request: $registrationRequest,
                presenter: $presenter
            );

            $this->flashBag->add(
                type: 'success',
                message: 'Account successfully created'
            );

            return $this->userAuthenticator->authenticateUser(
                user: $presenter->getViewModel()->getUser(),
                authenticator: $this->webAuthenticator,
                request: $request
            );
        }

        return new Response(
            $this->twig->render(
                name: 'security/registration.html.twig',
                context: [
                    "form" => $form->createView(),
                ]
            )
        );
    }
}

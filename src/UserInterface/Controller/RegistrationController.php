<?php

namespace App\UserInterface\Controller;

use Twig\Environment;
use Domain\Security\UseCase\Registration;
use App\UserInterface\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Domain\Security\Request\RegistrationRequest;
use Symfony\Component\Form\FormFactoryInterface;
use App\UserInterface\Presenter\RegistrationPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class RegistrationController
{
    public function __construct(
        private FormFactoryInterface $factory,
        private Environment $twig,
        private UrlGeneratorInterface $generator,
        private FlashBagInterface $flashBag
    ) {
    }

    public function __invoke(Request $request, Registration $registration): Response
    {
        $form = $this->factory
            ->create(type: RegistrationType::class)
            ->handleRequest(request: $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request = RegistrationRequest::create(
                email: $form->getData()->getEmail(),
                username: $form->getData()->getUsername(),
                plainPassword: $form->getData()->getPlainPassword()
            );
            $presenter = new RegistrationPresenter();
            $registration->execute(
                request: $request,
                presenter: $presenter
            );

            $this->flashBag->add(
                type: 'success',
                message: 'Account successfully created'
            );

            return new RedirectResponse(
                url: $this->generator->generate(
                    name: 'home'
                )
            );
        }

        return new Response($this->twig->render(
            name: 'registration.html.twig',
            context: [
                "form" => $form->createView(),
            ]
        ));
    }
}

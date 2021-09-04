<?php

namespace App\UserInterface\Controller;

use App\UserInterface\Form\RegistrationType;
use App\UserInterface\Presenter\RegistrationPresenter;
use Domain\Security\Request\RegistrationRequest;
use Domain\Security\UseCase\Registration;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class RegistrationController
{
    public function __construct(
        private FormFactoryInterface $factory,
        private Environment $twig,
        private UrlGeneratorInterface $generator
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

            return new RedirectResponse(
                url: $this->generator->generate(
                    name: 'home'
                )
            );
        }

        return new Response($this->twig->render(
            name: 'registration.html.twig',
            context: [
                "form" => $form->createView()
            ]
        ));
    }
}

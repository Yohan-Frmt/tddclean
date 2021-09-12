<?php

namespace App\UserInterface\Controller;

use App\UserInterface\ViewModel\Security\LoginViewModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class LoginController
{
    public function __construct(
        private FormFactoryInterface $factory,
        private Environment $twig,
        private UrlGeneratorInterface $generator,
        private FlashBagInterface $flashBag
    ) {
    }

    public function __invoke(AuthenticationUtils $utils): Response
    {
        return new Response(
            content: $this->twig->render(
                name: 'security/login.html.twig',
                context: [
                    'loginVM' => LoginViewModel::create($utils)
                ]
            )
        );
    }
}

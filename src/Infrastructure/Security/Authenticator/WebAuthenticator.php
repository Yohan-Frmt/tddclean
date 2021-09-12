<?php

namespace App\Infrastructure\Security\Authenticator;

use Domain\Security\UseCase\Login;
use Assert\AssertionFailedException;
use Domain\Security\Request\LoginRequest;
use Domain\Security\Response\LoginResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use App\Infrastructure\Security\Provider\UserProvider;
use Domain\Security\Presenter\LoginPresenterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class WebAuthenticator extends AbstractAuthenticator implements LoginPresenterInterface
{
    public const LOGIN_ROUTE = 'security_login';
    private LoginResponse $response;


    public function __construct(
        private UserProvider $provider,
        private Login $login,
        private UrlGeneratorInterface $generator,
        private FlashBagInterface $flashBag
    ) {
    }

    public function present(LoginResponse $response): void
    {
        $this->response = $response;
    }

    public function supports(Request $request): ?bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get(key: '_route')
            && $request->isMethod(method: Request::METHOD_POST);
    }

    public function authenticate(Request $request): PassportInterface
    {
        $loginRequest = LoginRequest::create(
            email: $request->get(key: 'username', default: ''),
            plainPassword: $request->get(key: 'password', default: '')
        );
        try {
            $this->login->execute($loginRequest, $this);
        } catch (AssertionFailedException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
        return new Passport(
            userBadge: new UserBadge(
                userIdentifier: $this->response->getUser()->getEmail(),
                userLoader: [
                    $this->provider,
                    'loadUserByIdentifier',
                ]
            ),
            credentials: new PasswordCredentials(
                password: $this->response->getPlainPassword()
            ),
            badges: [
                new CsrfTokenBadge(
                    csrfTokenId: 'authenticate',
                    csrfToken: $request->get(key: 'csrf_token')
                ),
            ]
        );
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        return new UsernamePasswordToken(
            user: $passport->getUser(),
            credentials: null,
            firewallName: $firewallName,
            roles: $passport->getUser()->getRoles()
        );
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->flashBag->add("success", 'Welcome Back!');
        return new RedirectResponse($this->generator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->flashBag->add("danger", $exception->getMessage());
        return null;
    }
}

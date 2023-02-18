<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    private const AUTHENTICATION_KEY = '_auth_username';

    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * This authenticator will be enabled when sending the _auth_username as a parameter.
     *
     * @param Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->query->has(self::AUTHENTICATION_KEY) === true;
    }

    /**
     * Performs the authentication user the SelfValidatingPassport.
     *
     * @param Request $request
     *
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $authenticationToken = $request->query->get(self::AUTHENTICATION_KEY);
        if (null === $authenticationToken) {
            throw new CustomUserMessageAuthenticationException('Please you need to provide a valid token.');
        }

        return new SelfValidatingPassport(new UserBadge(
            $authenticationToken,
            fn (string $identifier) => $this->repository->findOneBy(['authenticationToken' => $identifier])
        ));
    }

    /**
     * This method is fired when authentication succeed.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $firewallName
     *
     * @return ?Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * This method is fired when authentication failed.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return ?Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
    }
}

<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use UserBundle\Provider\PortalUserProvider;


class PortalUserAuthenticator implements SimplePreAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey)
    {
        // look for an apikey query parameter
        $authKey = $request->query->get('auth_key');

        return new PreAuthenticatedToken(
            'anon.',
            $authKey,
            $providerKey,
            []
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof PortalUserProvider) {
            return false;
        }

        $apiKey = $token->getCredentials();
        $employee = $userProvider->isAuthKeyValid($apiKey);

        if (!$employee) {

            return new AnonymousToken(
                rand(),
                'ss'
            );
        }

        $user = $userProvider->loadUserByUsername($employee->getFullName());

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }
}

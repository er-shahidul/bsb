<?php

namespace BoardMeetingBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class BoardMemberAuthenticator implements SimplePreAuthenticatorInterface
{
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof BoardMemberUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of BoardMemberUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();

        if (!$apiKey) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('Authentication Token does not exist.')
            );
        }

        $user = $userProvider->loadUserByUsername($apiKey);

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $targetUrl = '/board-meeting/';

        if (strpos($request->getPathInfo(), $targetUrl)  !== 0) {
            return NULL;
        }

        $apiKey = $request->cookies->get('_BOARD_MEETING_TOKEN');

        return new PreAuthenticatedToken(
            'member',
            $apiKey,
            $providerKey
        );
    }
}
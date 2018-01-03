<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;


class AppAuthenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if ($token->getUser() != null) {
            return $token;
        }

        $user = $this->isAuthenticate($userProvider, $token->getCredentials());

        if (null !== $ex = $this->handleExceptions($user)) {
            throw $ex;
        }

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new PreAuthenticatedToken(
            '',
            array(
                'username' => $username,
                'password' => $password
            ),
            $providerKey
        );
    }

    private function isAuthenticate(UserProviderInterface $userProvider, $credential)
    {
        $user = $userProvider->loadUserByUsername($credential['username']);
        if (!$user) {
            return null;
        }

        if ($this->isPasswordValid($user, $credential['password'])){
            return $user;
        }

        return null;
    }

    /**
     * @param $user
     */
    private function handleExceptions(UserInterface $user = null)
    {
        if (null == $user) {
            return new BadCredentialsException();
        }

        if (!$user->isEnabled()) {
            return new DisabledException('User account disabled');
        }

        /*if ($this->isPrivilegedUser($user)) {
            return new InsufficientAuthenticationException('Invalid username or password');
        }*/

        return null;
    }

    public function isPasswordValid(UserInterface $user, $raw)
    {
        // Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder
        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $raw, $user->getSalt());
    }
}

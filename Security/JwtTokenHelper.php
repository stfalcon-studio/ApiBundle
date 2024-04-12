<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use StfalconStudio\ApiBundle\Exception\DomainException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * JwtTokenHelper.
 */
class JwtTokenHelper
{
    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @throws DomainException
     *
     * @return JWTUserToken|JWTPostAuthenticationToken
     */
    public function getJwtUserToken(): JWTUserToken|JWTPostAuthenticationToken
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof JWTUserToken && !$token instanceof JWTPostAuthenticationToken) {
            throw new DomainException(sprintf('Token is not instance of %s nor of %s', JWTUserToken::class, JWTPostAuthenticationToken::class));
        }

        return $token;
    }
}

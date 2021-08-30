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
use StfalconStudio\ApiBundle\Exception\DomainException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * JwtTokenHelper.
 */
class JwtTokenHelper
{
    private TokenStorageInterface $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws DomainException
     *
     * @return JWTUserToken
     */
    public function getJwtUserToken(): JWTUserToken
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof JWTUserToken) {
            throw new DomainException(sprintf('Token is not instance of %s', JWTUserToken::class));
        }

        return $token;
    }
}

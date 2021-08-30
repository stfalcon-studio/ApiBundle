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

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWSProvider\JWSProviderInterface;
use Predis\Client;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Exception\LogicException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JwtBlackListService.
 */
class JwtBlackListService
{
    private JWSProviderInterface $jwsProvider;
    private Client $redisClientJwtBlackList;
    private JwtTokenHelper $jwtTokenHelper;
    private JwtCacheHelper $jwtCacheHelper;

    /**
     * @param JWSProviderInterface $jwsProvider
     * @param JwtTokenHelper       $jwtTokenHelper
     * @param JwtCacheHelper       $jwtCacheHelper
     */
    public function __construct(JWSProviderInterface $jwsProvider, JwtTokenHelper $jwtTokenHelper, JwtCacheHelper $jwtCacheHelper)
    {
        $this->jwsProvider = $jwsProvider;
        $this->jwtTokenHelper = $jwtTokenHelper;
        $this->jwtCacheHelper = $jwtCacheHelper;
    }

    /**
     * @param Client $redisClientJwtBlackList
     *
     * @required
     */
    public function setRedisClientJwtBlackList(Client $redisClientJwtBlackList): void
    {
        $this->redisClientJwtBlackList = $redisClientJwtBlackList;
    }

    /**
     * @throws LogicException
     */
    public function addCurrentTokenToBlackList(): void
    {
        $token = $this->jwtTokenHelper->getJwtUserToken();

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            throw new LogicException(sprintf('Current user is not instance of %s', UserInterface::class));
        }

        $this->addTokenToBlackList($token->getCredentials());
    }

    /**
     * @param string $rawToken
     *
     * @throws DomainException
     */
    public function addTokenToBlackList(string $rawToken): void
    {
        $jwtToken = $this->jwsProvider->load($rawToken);

        if (!$jwtToken->isExpired()) {
            $payload = $jwtToken->getPayload();

            if (!\array_key_exists('exp', $payload)) {
                throw new DomainException('Payload parameter `exp` in JWT token is not set');
            }
            if (!\array_key_exists('username', $payload)) {
                throw new DomainException('Payload parameter `username` in JWT token is not set');
            }

            $key = $this->jwtCacheHelper->getRedisKeyForUserRawToken($payload['username'], $rawToken);
            $this->redisClientJwtBlackList->setex($key, (int) $payload['exp'], null);
        }
    }

    /**
     * @param UserInterface                 $user
     * @param PreAuthenticationJWTUserToken $preAuthenticationJwtUserToken
     *
     * @return bool
     */
    public function tokenIsNotInBlackList(UserInterface $user, PreAuthenticationJWTUserToken $preAuthenticationJwtUserToken): bool
    {
        $key = $this->jwtCacheHelper->getRedisKeyForUserRawToken($user->getUserIdentifier(), $preAuthenticationJwtUserToken->getCredentials());
        $tokenIsInBlackList = (bool) $this->redisClientJwtBlackList->exists($key);

        return !$tokenIsInBlackList;
    }
}

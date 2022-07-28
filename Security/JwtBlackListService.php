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

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWSProvider\JWSProviderInterface;
use Predis\Client;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\LogicException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JwtBlackListService.
 */
class JwtBlackListService
{
    private Client $redisClientJwtBlackList;

    /**
     * @param JWSProviderInterface $jwsProvider
     * @param JwtTokenHelper       $jwtTokenHelper
     * @param JwtCacheHelper       $jwtCacheHelper
     */
    public function __construct(private readonly JWSProviderInterface $jwsProvider, private readonly JwtTokenHelper $jwtTokenHelper, private readonly JwtCacheHelper $jwtCacheHelper)
    {
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
     * @throws InvalidArgumentException
     */
    public function addCurrentTokenToBlackList(): void
    {
        $token = $this->jwtTokenHelper->getJwtUserToken();

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            throw new LogicException(sprintf('Current user is not instance of %s', UserInterface::class));
        }

        if (!\is_scalar($token->getCredentials())) {
            throw new InvalidArgumentException('Token cannot be casted to string');
        }

        $this->addTokenToBlackList((string) $token->getCredentials());
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
     * @param UserInterface $user
     * @param string        $token
     *
     * @return bool
     */
    public function tokenIsInBlackList(UserInterface $user, string $token): bool
    {
        $key = $this->jwtCacheHelper->getRedisKeyForUserRawToken($user->getUserIdentifier(), $token);

        return (bool) $this->redisClientJwtBlackList->exists($key);
    }
}

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

namespace StfalconStudio\ApiBundle\Tests\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWSProvider\JWSProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\LoadedJWS;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Exception\LogicException;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use StfalconStudio\ApiBundle\Security\JwtCacheHelper;
use StfalconStudio\ApiBundle\Security\JwtTokenHelper;
use Symfony\Component\Security\Core\User\UserInterface;

final class JwtBlackListServiceTest extends TestCase
{
    /** @var JWSProviderInterface|MockObject */
    private $jwsProvider;

    /** @var Client|MockObject */
    private $redisClientJwtBlackList;

    /** @var JwtTokenHelper|MockObject */
    private $jwtTokenHelper;

    /** @var JwtCacheHelper|MockObject */
    private $jwtCacheHelper;

    private JwtBlackListService $jwtBlackListService;

    protected function setUp(): void
    {
        $this->jwsProvider = $this->createMock(JWSProviderInterface::class);
        $this->redisClientJwtBlackList = $this->createMock(Client::class);
        $this->jwtTokenHelper = $this->createMock(JwtTokenHelper::class);
        $this->jwtCacheHelper = $this->createMock(JwtCacheHelper::class);

        $this->jwtBlackListService = new JwtBlackListService(
            $this->jwsProvider,
            $this->redisClientJwtBlackList,
            $this->jwtTokenHelper,
            $this->jwtCacheHelper,
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->jwsProvider,
            $this->redisClientJwtBlackList,
            $this->jwtTokenHelper,
            $this->jwtCacheHelper,
            $this->jwtBlackListService,
        );
    }

    public function testAddCurrentTokenToBlackList(): void
    {
        $token = $this->createMock(JWTUserToken::class);

        $this->jwtTokenHelper
            ->expects(self::once())
            ->method('getJwtUserToken')
            ->willReturn($token)
        ;

        $user = $this->createMock(UserInterface::class);

        $token
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $token
            ->expects(self::once())
            ->method('getCredentials')
            ->willReturn('test')
        ;

        $loadedJWS = new LoadedJWS(['exp' => 1], true);

        $this->jwsProvider
            ->expects(self::once())
            ->method('load')
            ->with('test')
            ->willReturn($loadedJWS)
        ;

        $this->jwtBlackListService->addCurrentTokenToBlackList();
    }

    public function testAddCurrentTokenToBlackListWithException(): void
    {
        $token = $this->createMock(JWTUserToken::class);

        $this->jwtTokenHelper
            ->expects(self::once())
            ->method('getJwtUserToken')
            ->willReturn($token)
        ;

        $token
            ->expects(self::once())
            ->method('getUser')
            ->willReturn(new \stdClass())
        ;

        $this->expectException(LogicException::class);

        $this->jwtBlackListService->addCurrentTokenToBlackList();
    }

    public function testAddTokenToBlackList(): void
    {
        $loadedJWS = new LoadedJWS(['exp' => 2147483648, 'username' => 'test_username'], true);

        $this->jwsProvider
            ->expects(self::once())
            ->method('load')
            ->with('raw token')
            ->willReturn($loadedJWS)
        ;

        $this->jwtCacheHelper
            ->expects(self::once())
            ->method('getRedisKeyForUserRawToken')
            ->with('test_username', 'raw token')
            ->willReturn('key')
        ;

        $this->redisClientJwtBlackList
            ->expects(self::once())
            ->method('__call')
            ->with(self::equalTo('setex'), ['key', 2147483648, null])
            ->willReturn(0)
        ;

        $this->jwtBlackListService->addTokenToBlackList('raw token');
    }

    public function testAddTokenToBlackListWithMissedExpiration(): void
    {
        $loadedJWS = new LoadedJWS(['username' => 'test_username'], true);

        $this->jwsProvider
            ->expects(self::once())
            ->method('load')
            ->with('raw token')
            ->willReturn($loadedJWS)
        ;

        $this->jwtCacheHelper
            ->expects(self::never())
            ->method('getRedisKeyForUserRawToken')
        ;

        $this->redisClientJwtBlackList
            ->expects(self::never())
            ->method('__call')
        ;

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Payload parameter `exp` in JWT token is not set');

        $this->jwtBlackListService->addTokenToBlackList('raw token');
    }

    public function testAddTokenToBlackListWithMissedUsername(): void
    {
        $loadedJWS = new LoadedJWS(['exp' => 2147483648], true);

        $this->jwsProvider
            ->expects(self::once())
            ->method('load')
            ->with('raw token')
            ->willReturn($loadedJWS)
        ;

        $this->jwtCacheHelper
            ->expects(self::never())
            ->method('getRedisKeyForUserRawToken')
        ;

        $this->redisClientJwtBlackList
            ->expects(self::never())
            ->method('__call')
        ;

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Payload parameter `username` in JWT token is not set');

        $this->jwtBlackListService->addTokenToBlackList('raw token');
    }

    public function testTokenIsNotInBlackList(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn('test_username')
        ;

        $jwtUserToken = new PreAuthenticationJWTUserToken('test_credentials');

        $this->jwtCacheHelper
            ->expects(self::once())
            ->method('getRedisKeyForUserRawToken')
            ->with('test_username', 'test_credentials')
            ->willReturn('test_key')
        ;

        $this->redisClientJwtBlackList
            ->expects(self::once())
            ->method('__call')
            ->with(self::equalTo('exists'), ['test_key'])
            ->willReturn(0)
        ;

        self::assertTrue($this->jwtBlackListService->tokenIsNotInBlackList($user, $jwtUserToken));
    }

    public function testTokenIsInBlackList(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn('test_username')
        ;

        $jwtUserToken = new PreAuthenticationJWTUserToken('test_credentials');

        $this->jwtCacheHelper
            ->expects(self::once())
            ->method('getRedisKeyForUserRawToken')
            ->with('test_username', 'test_credentials')
            ->willReturn('test_key')
        ;

        $this->redisClientJwtBlackList
            ->expects(self::once())
            ->method('__call')
            ->with(self::equalTo('exists'), ['test_key'])
            ->willReturn(1)
        ;

        self::assertFalse($this->jwtBlackListService->tokenIsNotInBlackList($user, $jwtUserToken));
    }
}

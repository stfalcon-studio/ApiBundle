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

namespace StfalconStudio\ApiBundle\Tests\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseJWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Security\Guard\JwtTokenAuthenticator;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class JwtTokenAuthenticatorTest extends TestCase
{
    /** @var JWTTokenManagerInterface|MockObject */
    private $jwtManager;

    /** @var EventDispatcherInterface|MockObject */
    private $eventDispatcher;

    /** @var TokenExtractorInterface|MockObject */
    private $tokenExtractor;

    /** @var JwtBlackListService|MockObject */
    private $jwtBlackListService;

    private JwtTokenAuthenticator $jwtTokenAuthenticator;

    protected function setUp(): void
    {
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->tokenExtractor = $this->createMock(TokenExtractorInterface::class);
        $this->jwtBlackListService = $this->createMock(JwtBlackListService::class);

        $this->jwtTokenAuthenticator = new JwtTokenAuthenticator(
            $this->jwtManager,
            $this->eventDispatcher,
            $this->tokenExtractor,
            $this->jwtBlackListService,
            $this->createStub(TokenStorageInterface::class)
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->jwtManager,
            $this->eventDispatcher,
            $this->tokenExtractor,
            $this->jwtBlackListService,
            $this->jwtTokenAuthenticator,
        );
    }

    public function testConstructor(): void
    {
        self::assertInstanceOf(BaseJWTTokenAuthenticator::class, $this->jwtTokenAuthenticator);
    }

    public function testCheckCredentialsByComparingTimestampsOfCredentials(): void
    {
        $credentials = new PreAuthenticationJWTUserToken('');
        $credentials->setPayload(['iat' => '1893455999']); // 2029-12-31 23:59:59
        $user = $this->createMock(DummyUser::class);

        $user
            ->method('getCredentialsLastChangedAt')
            ->willReturn(\DateTime::createFromFormat('U', '1893456000')) // 2030-01-01 00:00:00
        ;

        $this->jwtBlackListService
            ->expects(self::never())
            ->method('tokenIsNotInBlackList')
        ;

        self::assertFalse($this->jwtTokenAuthenticator->checkCredentials($credentials, $user));
    }

    public function testCheckCredentialsByComparingTimestampsOfCredentialsForSameDates(): void
    {
        $credentials = new PreAuthenticationJWTUserToken('');
        $credentials->setPayload(['iat' => '1893456000']); // 2030-01-01 00:00:00
        $user = $this->createMock(DummyUser::class);

        $user
            ->method('getCredentialsLastChangedAt')
            ->willReturn(\DateTime::createFromFormat('U', '1893456000')) // 2030-01-01 00:00:00
        ;

        $this->jwtBlackListService
            ->expects(self::once())
            ->method('tokenIsNotInBlackList')
            ->with($user, $credentials)
            ->willReturn(true)
        ;

        self::assertTrue($this->jwtTokenAuthenticator->checkCredentials($credentials, $user));
    }

    public function testCheckCredentialsByComparingTimestampsOfCredentialsWithException(): void
    {
        $user = $this->createMock(DummyUser::class);

        $user
            ->method('getCredentialsLastChangedAt')
            ->willReturn(\DateTime::createFromFormat('U', '1893456000')) // 2030-01-01 00:00:00
        ;

        $this->jwtBlackListService
            ->expects(self::never())
            ->method('tokenIsNotInBlackList')
        ;

        $this->expectException(DomainException::class);

        $this->jwtTokenAuthenticator->checkCredentials([], $user);
    }

    public function testCheckCredentialsByComparingTimestampsOfCredentialsWithExceptionForMissedIat(): void
    {
        $credentials = new PreAuthenticationJWTUserToken('');
        $user = $this->createMock(DummyUser::class);

        $user
            ->method('getCredentialsLastChangedAt')
            ->willReturn(\DateTime::createFromFormat('U', '1893456000')) // 2030-01-01 00:00:00
        ;

        $this->jwtBlackListService
            ->expects(self::never())
            ->method('tokenIsNotInBlackList')
        ;

        $this->expectException(DomainException::class);

        $this->jwtTokenAuthenticator->checkCredentials($credentials, $user);
    }

    public function testCheckCredentialsByCheckingTokenBlackList(): void
    {
        $credentials = new PreAuthenticationJWTUserToken('');
        $user = $this->createMock(DummyUser::class);

        $this->jwtBlackListService
            ->expects(self::once())
            ->method('tokenIsNotInBlackList')
            ->with($user, $credentials)
            ->willReturn(true)
        ;

        self::assertTrue($this->jwtTokenAuthenticator->checkCredentials($credentials, $user));
    }
}

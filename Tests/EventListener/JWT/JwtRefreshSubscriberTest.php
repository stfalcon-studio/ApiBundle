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

namespace StfalconStudio\ApiBundle\Tests\EventListener\JWT;

use Gesdinet\JWTRefreshTokenBundle\Event\RefreshEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Entity\JWT\RefreshToken;
use StfalconStudio\ApiBundle\EventListener\JWT\JwtRefreshSubscriber;
use StfalconStudio\ApiBundle\Exception\JWT\InvalidRefreshTokenException;
use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

final class JwtRefreshSubscriberTest extends TestCase
{
    /** @var RefreshEvent|MockObject */
    private $refreshEvent;

    /** @var PostAuthenticationGuardToken|MockObject */
    private $preAuthenticatedToken;

    /** @var RefreshToken|MockObject */
    private $refreshToken;

    /** @var CredentialsInterface|MockObject */
    private $user;

    /** @var JwtRefreshSubscriber */
    private $subscriber;

    protected function setUp(): void
    {
        $this->refreshEvent = $this->createMock(RefreshEvent::class);
        $this->user = $this->createMock(CredentialsInterface::class);
        $this->preAuthenticatedToken = $this->createMock(PostAuthenticationGuardToken::class);
        $this->refreshToken = $this->createMock(RefreshToken::class);
        $this->subscriber = new JwtRefreshSubscriber();
    }

    protected function tearDown(): void
    {
        unset(
            $this->refreshEvent,
            $this->user,
            $this->preAuthenticatedToken,
            $this->refreshToken,
            $this->subscriber,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = [
            'gesdinet.refresh_token' => 'processRefreshToken',
            RefreshEvent::class => 'processRefreshToken',
        ];
        $actual = [];
        foreach (JwtRefreshSubscriber::getSubscribedEvents() as $key => $event) {
            $actual[$key] = $event;
        }

        self::assertSame($expected, $actual);
    }

    public function testProcessRefreshTokenWithException(): void
    {
        $this->refreshEvent
            ->expects(self::once())
            ->method('getPreAuthenticatedToken')
            ->willReturn($this->preAuthenticatedToken)
        ;
        $this->refreshEvent
            ->expects(self::once())
            ->method('getRefreshToken')
            ->willReturn($this->refreshToken)
        ;

        $this->user
            ->expects(self::once())
            ->method('getCredentialsLastChangedAt')
            ->willReturn(new \DateTime('2030-01-01 00:00:01'))
        ;

        $this->preAuthenticatedToken
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($this->user)
        ;

        $this->refreshToken
            ->expects(self::once())
            ->method('getCreatedAt')
            ->willReturn(new \DateTimeImmutable('2030-01-01 00:00:00'))
        ;

        $this->expectException(InvalidRefreshTokenException::class);

        $this->subscriber->processRefreshToken($this->refreshEvent);
    }

    public function testProcessRefreshTokenWithoutException(): void
    {
        $this->refreshEvent
            ->expects(self::once())
            ->method('getPreAuthenticatedToken')
            ->willReturn($this->preAuthenticatedToken)
        ;
        $this->refreshEvent
            ->expects(self::once())
            ->method('getRefreshToken')
            ->willReturn($this->refreshToken)
        ;

        $this->user
            ->expects(self::once())
            ->method('getCredentialsLastChangedAt')
            ->willReturn(new \DateTime('2030-01-01 00:00:01'))
        ;

        $this->preAuthenticatedToken
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($this->user)
        ;

        $this->refreshToken
            ->expects(self::once())
            ->method('getCreatedAt')
            ->willReturn(new \DateTimeImmutable('2030-01-01 00:00:01'))
        ;

        $this->subscriber->processRefreshToken($this->refreshEvent);
    }
}

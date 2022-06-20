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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Security;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\Security\CheckVerifiedUserSubscriber;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

final class CheckVerifiedUserSubscriberTest extends TestCase
{
    /** @var JwtBlackListService|MockObject */
    private JwtBlackListService|MockObject $jwtBlackListService;

    private CheckVerifiedUserSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->jwtBlackListService = $this->createMock(JwtBlackListService::class);
        $this->subscriber = new CheckVerifiedUserSubscriber($this->jwtBlackListService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->jwtBlackListService,
            $this->subscriber,
        );
    }

    public function testOnCheckPassportWithoutException(): void
    {
        $user = $this->createMock(DummyUser::class);
        $user
            ->expects(self::once())
            ->method('getCredentialsLastChangedAt')
            ->willReturn(null)
        ;

        $passport = $this->createMock(Passport::class);
        $passport
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $passport
            ->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['payload'], ['token'])
            ->willReturnOnConsecutiveCalls(['iat' => 1], 'qwerty')
        ;

        $event = $this->createMock(CheckPassportEvent::class);
        $event
            ->expects(self::once())
            ->method('getPassport')
            ->willReturn($passport)
        ;

        $this->jwtBlackListService
            ->expects(self::once())
            ->method('tokenIsNotInBlackList')
            ->with($user, 'qwerty')
            ->willReturn(false)
        ;

        $this->subscriber->onCheckPassport($event);
    }

    public function testOnCheckPassportWithCredentialsWereChangedException(): void
    {
        $user = $this->createMock(DummyUser::class);
        $user
            ->expects(self::exactly(2))
            ->method('getCredentialsLastChangedAt')
            ->willReturn((new \DateTime())->setTimestamp(2))
        ;

        $passport = $this->createMock(Passport::class);
        $passport
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $passport
            ->expects(self::once())
            ->method('getAttribute')
            ->with('payload')
            ->willReturn(['iat' => 1])
        ;

        $event = $this->createMock(CheckPassportEvent::class);
        $event
            ->expects(self::once())
            ->method('getPassport')
            ->willReturn($passport)
        ;

        $this->jwtBlackListService
            ->expects(self::never())
            ->method('tokenIsNotInBlackList')
        ;

        $this->expectErrorMessage('Credentials were changed.');
        $this->expectException(BadCredentialsException::class);

        $this->subscriber->onCheckPassport($event);
    }

    public function testOnCheckPassportWithTokenInBlackListException(): void
    {
        $user = $this->createMock(DummyUser::class);
        $user
            ->expects(self::exactly(2))
            ->method('getCredentialsLastChangedAt')
            ->willReturn((new \DateTime())->setTimestamp(2))
        ;

        $passport = $this->createMock(Passport::class);
        $passport
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($user)
        ;
        $passport
            ->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['payload'], ['token'])
            ->willReturnOnConsecutiveCalls(['iat' => 2], 'qwerty')
        ;

        $event = $this->createMock(CheckPassportEvent::class);
        $event
            ->expects(self::once())
            ->method('getPassport')
            ->willReturn($passport)
        ;

        $this->jwtBlackListService
            ->expects(self::once())
            ->method('tokenIsNotInBlackList')
            ->with($user, 'qwerty')
            ->willReturn(true)
        ;

        $this->expectErrorMessage('Token in the black list.');
        $this->expectException(BadCredentialsException::class);

        $this->subscriber->onCheckPassport($event);
    }
}

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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Event\User\UserLogoutEvent;
use StfalconStudio\ApiBundle\Event\User\UserPasswordChangedEvent;
use StfalconStudio\ApiBundle\EventListener\JWT\TokenBlackListSubscriber;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;

final class TokenBlackListSubscriberTest extends TestCase
{
    /** @var JwtBlackListService|MockObject */
    private $tokenBlackListService;

    private TokenBlackListSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->tokenBlackListService = $this->createMock(JwtBlackListService::class);
        $this->subscriber = new TokenBlackListSubscriber($this->tokenBlackListService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->tokenBlackListService,
            $this->subscriber,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = [
            UserPasswordChangedEvent::class => 'addCurrentJwtTokenToBlackList',
            UserLogoutEvent::class => 'addCurrentJwtTokenToBlackList',
        ];
        $actual = [];
        foreach (TokenBlackListSubscriber::getSubscribedEvents() as $key => $event) {
            $actual[$key] = $event;
        }

        self::assertSame($expected, $actual);
    }

    public function testOnAuthenticationFailureResponse(): void
    {
        $this->tokenBlackListService
            ->expects(self::once())
            ->method('addCurrentTokenToBlackList')
        ;

        $this->subscriber->addCurrentJwtTokenToBlackList();
    }
}

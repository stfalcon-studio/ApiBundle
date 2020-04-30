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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\Jwt\JwtSubscriber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

final class JwtSubscriberTest extends TestCase
{
    /** @var TranslatorInterface|MockObject */
    private $translator;

    /** @var JwtSubscriber */
    private $subscriber;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->subscriber = new JwtSubscriber($this->translator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->translator,
            $this->subscriber,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = [
            AuthenticationFailureEvent::class => 'onAuthenticationFailureResponse',
            JWTInvalidEvent::class => 'onAuthenticationFailureResponse',
            JWTNotFoundEvent::class => 'onAuthenticationFailureResponse',
            JWTExpiredEvent::class => 'onAuthenticationFailureResponse',
            Events::AUTHENTICATION_FAILURE => 'onAuthenticationFailureResponse',
            Events::JWT_INVALID => 'onAuthenticationFailureResponse',
            Events::JWT_NOT_FOUND => 'onAuthenticationFailureResponse',
            Events::JWT_EXPIRED => 'onAuthenticationFailureResponse',
        ];
        $actual = [];
        foreach (JwtSubscriber::getSubscribedEvents() as $key => $event) {
            $actual[$key] = $event;
        }

        self::assertSame($expected, $actual);
    }

    /**
     * @param MockObject|AuthenticationFailureEvent|string $event
     * @param string                                       $message
     *
     * @dataProvider dataProviderForTestOnAuthenticationFailureResponse
     */
    public function testOnAuthenticationFailureResponse($event, string $message): void
    {
        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($message)
            ->willReturn('translated message')
        ;

        $event
            ->expects(self::once())
            ->method('setResponse')
            ->with($this->isInstanceOf(JsonResponse::class))
        ;

        $this->subscriber->onAuthenticationFailureResponse($event);
    }

    public function dataProviderForTestOnAuthenticationFailureResponse(): iterable
    {
        yield [$this->createMock(AuthenticationFailureEvent::class), 'unauthorised_user_message'];
        yield [$this->createMock(JWTInvalidEvent::class), 'invalid_jwt_token_message'];
        yield [$this->createMock(JWTNotFoundEvent::class), 'not_found_jwt_token_message'];
        yield [$this->createMock(JWTExpiredEvent::class), 'expired_jwt_token_message'];
    }
}

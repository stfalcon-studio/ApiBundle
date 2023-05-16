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

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\JWT\JwtSubscriber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

final class JwtSubscriberTest extends TestCase
{
    private TranslatorInterface|MockObject $translator;
    private JwtSubscriber $subscriber;

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

    public function testOnAuthenticationFailureResponse(): void
    {
        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('unauthorised_user_message')
            ->willReturn('translated message')
        ;

        $event = $this->createMock(AuthenticationFailureEvent::class);

        $event
            ->expects(self::once())
            ->method('setResponse')
            ->with($this->callback(static function (JsonResponse $response) {
                return '{"error":"unauthorised_user","errorDescription":"translated message"}' === $response->getContent()
                       && JsonResponse::HTTP_UNAUTHORIZED === $response->getStatusCode();
            }))
        ;

        $this->subscriber->onAuthenticationFailureResponse($event);
    }

    public function testOnJWTInvalidEvent(): void
    {
        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('invalid_jwt_token_message')
            ->willReturn('translated message')
        ;

        $event = $this->createMock(JWTInvalidEvent::class);

        $event
            ->expects(self::once())
            ->method('setResponse')
            ->with($this->callback(static function (JsonResponse $response) {
                return '{"error":"unauthorised_user","errorDescription":"translated message"}' === $response->getContent()
                    && JsonResponse::HTTP_UNAUTHORIZED === $response->getStatusCode();
            }))
        ;

        $this->subscriber->onAuthenticationFailureResponse($event);
    }

    public function testOnJWTNotFoundEvent(): void
    {
        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('not_found_jwt_token_message')
            ->willReturn('translated message')
        ;

        $event = $this->createMock(JWTNotFoundEvent::class);

        $event
            ->expects(self::once())
            ->method('setResponse')
            ->with($this->callback(static function (JsonResponse $response) {
                return '{"error":"unauthorised_user","errorDescription":"translated message"}' === $response->getContent()
                    && JsonResponse::HTTP_UNAUTHORIZED === $response->getStatusCode();
            }))
        ;

        $this->subscriber->onAuthenticationFailureResponse($event);
    }

    public function testOnJWTExpiredEvent(): void
    {
        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('expired_jwt_token_message')
            ->willReturn('translated message')
        ;

        $event = $this->createMock(JWTExpiredEvent::class);

        $event
            ->expects(self::once())
            ->method('setResponse')
            ->with($this->callback(static function (JsonResponse $response) {
                return '{"error":"unauthorised_user","errorDescription":"translated message"}' === $response->getContent()
                    && JsonResponse::HTTP_UNAUTHORIZED === $response->getStatusCode();
            }))
        ;

        $this->subscriber->onAuthenticationFailureResponse($event);
    }
}

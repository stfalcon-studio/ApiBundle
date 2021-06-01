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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Kernel;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\EventListener\Kernel\JsonDecoderListener;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * JsonDecoderListenerTest.
 */
final class JsonDecoderListenerTest extends TestCase
{
    /** @var RequestEvent|MockObject */
    private $requestEvent;

    /** @var Request|MockObject */
    private $request;

    /** @var InputBag */
    private $parameterBag;

    /** @var JsonDecoderListener */
    private $jsonDecoderSubscriber;

    protected function setUp(): void
    {
        $this->requestEvent = $this->createMock(RequestEvent::class);
        $this->request = $this->createMock(Request::class);
        $this->parameterBag = new InputBag();
        $this->request->request = $this->parameterBag;

        $this->jsonDecoderSubscriber = new JsonDecoderListener();
    }

    protected function tearDown(): void
    {
        unset(
            $this->requestEvent,
            $this->request,
            $this->parameterBag,
            $this->jsonDecoderSubscriber,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = [
            RequestEvent::class => '__invoke',
        ];
        $actual = [];
        foreach (JsonDecoderListener::getSubscribedEvents() as $key => $event) {
            $actual[$key] = $event;
        }

        self::assertSame($expected, $actual);
    }

    public function testNotMasterRequest(): void
    {
        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(false)
        ;

        $this->requestEvent
            ->expects(self::never())
            ->method('getRequest')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }

    public function testContentTypeNotSpecified(): void
    {
        $this->request->headers = new HeaderBag();

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::never())
            ->method('getContent')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }

    public function testContentIsNotJson(): void
    {
        $this->request->headers = new HeaderBag([
            'Content-Type' => 'text/html',
        ]);

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::never())
            ->method('getContent')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }

    public function testContentIsIncorrectJson(): void
    {
        $this->request->headers = new HeaderBag([
            'Content-Type' => 'application/json',
        ]);

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('{')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }

    public function testContentAfterDecodingIsArray(): void
    {
        $this->request->headers = new HeaderBag([
            'Content-Type' => 'application/json',
        ]);

        $parameterBag = new ParameterBag([
            'key' => 'value',
        ]);

        $this->request->request = $parameterBag;

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::once())
            ->method('getContent')
            ->willReturn('{"key":"value"}')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertEquals($parameterBag, $this->request->request);
        self::assertSame(['key' => 'value'], $this->request->request->all());
    }

    public function testContentTypeIsNull(): void
    {
        $this->request->headers = new HeaderBag([
            'Content-Type' => null,
        ]);

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::never())
            ->method('getContent')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }

    public function testContentTypeIsArray(): void
    {
        $this->request->headers = new HeaderBag([
            'Content-Type' => [1, 2],
        ]);

        $this->requestEvent
            ->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true)
        ;

        $this->requestEvent
            ->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request
            ->expects(self::never())
            ->method('getContent')
        ;

        $this->jsonDecoderSubscriber->__invoke($this->requestEvent);

        self::assertSame($this->parameterBag, $this->request->request);
    }
}

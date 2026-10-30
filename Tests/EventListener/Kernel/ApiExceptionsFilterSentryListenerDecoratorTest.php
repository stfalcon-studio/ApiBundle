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

use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sentry\SentryBundle\EventListener\ErrorListener;
use Sentry\State\HubInterface;
use StfalconStudio\ApiBundle\EventListener\Kernel\ApiExceptionFormatterListener;
use StfalconStudio\ApiBundle\EventListener\Kernel\ApiExceptionsFilterSentryListenerDecorator;
use StfalconStudio\ApiBundle\Service\Exception\ExceptionResponseFactory;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ApiExceptionsFilterSentryListenerDecoratorTest extends TestCase
{
    private HubInterface|MockObject $hub;
    private ApiExceptionsFilterSentryListenerDecorator $decorator;

    protected function setUp(): void
    {
        $this->hub = $this->createMock(HubInterface::class);

        $this->decorator = new ApiExceptionsFilterSentryListenerDecorator(
            new ErrorListener($this->hub),
            new ApiExceptionFormatterListener(
                'api.test',
                'prod',
                $this->createMock(ExceptionResponseProcessorInterface::class),
                $this->createMock(ExceptionResponseFactory::class),
            ),
        );
    }

    protected function tearDown(): void
    {
        unset($this->hub, $this->decorator);
    }

    public function testLeavesCustomExceptionOnApiHostToTheFormatter(): void
    {
        $this->hub->expects(self::never())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('api.test', HttpKernelInterface::MAIN_REQUEST, new DummyCustomAppLoggableException('Jericho fell')));
    }

    public function testLeavesNotLoggableCustomExceptionOnApiHostToTheFormatter(): void
    {
        $this->hub->expects(self::never())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('api.test', HttpKernelInterface::MAIN_REQUEST, new DummyCustomAppNotLoggableException('Jericho fell')));
    }

    public function testLeavesLockExceptionOnApiHostToTheFormatter(): void
    {
        $this->hub->expects(self::never())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('api.test', HttpKernelInterface::MAIN_REQUEST, new OptimisticLockException('Nehemiah rebuilt the wall', null)));
    }

    public function testReportsCustomExceptionOnAnotherHost(): void
    {
        $this->hub->expects(self::once())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('hub.test', HttpKernelInterface::MAIN_REQUEST, new DummyCustomAppLoggableException('Jericho fell')));
    }

    public function testReportsCustomExceptionInSubRequest(): void
    {
        $this->hub->expects(self::once())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('api.test', HttpKernelInterface::SUB_REQUEST, new DummyCustomAppLoggableException('Jericho fell')));
    }

    public function testReportsOtherExceptionOnApiHost(): void
    {
        $this->hub->expects(self::once())->method('captureEvent');

        $this->decorator->handleExceptionEvent($this->createEvent('api.test', HttpKernelInterface::MAIN_REQUEST, new \RuntimeException('Jericho fell')));
    }

    private function createEvent(string $host, int $requestType, \Throwable $exception): ExceptionEvent
    {
        return new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            Request::create(\sprintf('http://%s/api/routes', $host)),
            $requestType,
            $exception,
        );
    }
}

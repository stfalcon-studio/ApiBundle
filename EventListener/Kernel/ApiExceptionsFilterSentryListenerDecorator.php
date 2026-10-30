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

namespace StfalconStudio\ApiBundle\EventListener\Kernel;

use Sentry\SentryBundle\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Leaves to {@see ApiExceptionFormatterListener} the exceptions it reports.
 */
final readonly class ApiExceptionsFilterSentryListenerDecorator
{
    /**
     * @param ErrorListener                 $sentryErrorListener
     * @param ApiExceptionFormatterListener $apiExceptionFormatterListener
     */
    public function __construct(private ErrorListener $sentryErrorListener, private ApiExceptionFormatterListener $apiExceptionFormatterListener)
    {
    }

    /**
     * @param ExceptionEvent $event
     */
    public function handleExceptionEvent(ExceptionEvent $event): void
    {
        if ($event->isMainRequest()
            && $this->apiExceptionFormatterListener->isApiRequest($event->getRequest())
            && $this->isReportedByApiExceptionFormatterListener($event->getThrowable())
        ) {
            return;
        }

        $this->sentryErrorListener->handleExceptionEvent($event);
    }

    /**
     * @param \Throwable $exception
     *
     * @return bool
     */
    private function isReportedByApiExceptionFormatterListener(\Throwable $exception): bool
    {
        foreach ($this->apiExceptionFormatterListener->getSentryReportedExceptionClasses() as $exceptionClass) {
            if ($exception instanceof $exceptionClass) {
                return true;
            }
        }

        return false;
    }
}

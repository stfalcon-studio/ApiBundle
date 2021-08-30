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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * JsonDecoderListener.
 */
final class JsonDecoderListener implements EventSubscriberInterface
{
    public const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @param RequestEvent $event
     */
    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->headers->has(self::HEADER_CONTENT_TYPE)) {
            $contentType = $request->headers->all(self::HEADER_CONTENT_TYPE);

            if (\is_array($contentType)) {
                $contentType = \implode(',', $contentType);
            }
            $contentType = (string) $contentType;

            if (\mb_substr_count($contentType, 'application/json') > 0) {
                $data = \json_decode((string) $request->getContent(), true);

                if (\is_array($data)) {
                    $request->request = new InputBag($data);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield RequestEvent::class => '__invoke';
    }
}

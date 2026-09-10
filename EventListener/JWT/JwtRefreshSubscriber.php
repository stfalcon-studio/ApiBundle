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

namespace StfalconStudio\ApiBundle\EventListener\JWT;

use Gesdinet\JWTRefreshTokenBundle\Event\RefreshEvent;
use StfalconStudio\ApiBundle\Exception\JWT\InvalidRefreshTokenException;
use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use StfalconStudio\ApiBundle\Model\JWT\CreatedAtAwareRefreshTokenInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * JwtRefreshSubscriber.
 */
final class JwtRefreshSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield 'gesdinet.refresh_token' => 'processRefreshToken';
        yield RefreshEvent::class => 'processRefreshToken';
    }

    /**
     * @param RefreshEvent $event
     *
     * @throws InvalidRefreshTokenException
     */
    public function processRefreshToken(RefreshEvent $event): void
    {
        $user = $event->getToken()->getUser();
        $refreshToken = $event->getRefreshToken();

        if (!$user instanceof CredentialsInterface || !$refreshToken instanceof CreatedAtAwareRefreshTokenInterface) {
            return;
        }

        $userCredentialsLastChangedAt = $user->getCredentialsLastChangedAt();

        if (!$userCredentialsLastChangedAt instanceof \DateTimeInterface) {
            return;
        }

        if ($refreshToken->getCreatedAt()->getTimestamp() < $userCredentialsLastChangedAt->getTimestamp()) {
            throw new InvalidRefreshTokenException();
        }
    }
}

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
use StfalconStudio\ApiBundle\Entity\JWT\RefreshToken;
use StfalconStudio\ApiBundle\Exception\JWT\InvalidRefreshTokenException;
use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
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

        if ($user instanceof CredentialsInterface) {
            $refreshToken = $event->getRefreshToken();

            if ($refreshToken instanceof RefreshToken) {
                $userCredentialsLastChangedAt = $user->getCredentialsLastChangedAt();
                $refreshTokenCreatedAt = $refreshToken->getCreatedAt()->getTimestamp();

                if ($userCredentialsLastChangedAt instanceof \DateTimeInterface && $refreshTokenCreatedAt < $userCredentialsLastChangedAt->getTimestamp()) {
                    throw new InvalidRefreshTokenException();
                }
            }
        }
    }
}

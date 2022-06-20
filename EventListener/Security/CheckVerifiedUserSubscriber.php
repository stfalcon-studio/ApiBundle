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

namespace StfalconStudio\ApiBundle\EventListener\Security;

use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

/**
 * CheckVerifiedUserSubscriber.
 */
final class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private readonly JwtBlackListService $tokenBlackListService;

    /**
     * @param JwtBlackListService $tokenBlackListService
     */
    public function __construct(JwtBlackListService $tokenBlackListService)
    {
        $this->tokenBlackListService = $tokenBlackListService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield CheckPassportEvent::class => 'onCheckPassport';
    }

    /**
     * @param CheckPassportEvent $event
     *
     * @throws BadCredentialsException
     *
     * @return void
     */
    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        $user = $passport->getUser();

        $payload = $passport->getAttribute('payload');
        if ($user instanceof CredentialsInterface && $user->getCredentialsLastChangedAt() instanceof \DateTime && is_array($payload) && (int) $payload['iat'] < $user->getCredentialsLastChangedAt()->getTimestamp()) {
            throw new BadCredentialsException('Credentials were changed.');
        }

        $token = $passport->getAttribute('token');
        if (\is_string($token) && $this->tokenBlackListService->tokenIsNotInBlackList($user, $token)) {
            throw new BadCredentialsException('Token in the black list.');
        }
    }
}

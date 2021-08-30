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

use StfalconStudio\ApiBundle\Event\User\UserLogoutEvent;
use StfalconStudio\ApiBundle\Event\User\UserPasswordChangedEvent;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * TokenBlackListSubscriber.
 */
final class TokenBlackListSubscriber implements EventSubscriberInterface
{
    private JwtBlackListService $jwtBlackListService;

    /**
     * @param JwtBlackListService $tokenBlackListService
     */
    public function __construct(JwtBlackListService $tokenBlackListService)
    {
        $this->jwtBlackListService = $tokenBlackListService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield UserPasswordChangedEvent::class => 'addCurrentJwtTokenToBlackList';
        yield UserLogoutEvent::class => 'addCurrentJwtTokenToBlackList';
    }

    /**
     * Add current JWT token to black list.
     */
    public function addCurrentJwtTokenToBlackList(): void
    {
        $this->jwtBlackListService->addCurrentTokenToBlackList();
    }
}

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

namespace StfalconStudio\ApiBundle\Tests\Event\User;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Event\User\AbstractUserEvent;
use StfalconStudio\ApiBundle\Event\User\UserCredentialsChangedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserCredentialsChangedEventTest.
 */
final class UserCredentialsChangedEventTest extends TestCase
{
    public function testConstruct(): void
    {
        $user = $this->createStub(UserInterface::class);
        $event = new UserCredentialsChangedEvent($user);

        self::assertSame($user, $event->getUser());
        self::assertInstanceOf(AbstractUserEvent::class, $event);
    }
}

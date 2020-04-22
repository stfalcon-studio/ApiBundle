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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AbstractBaseUserEventTest.
 */
final class AbstractBaseUserEventTest extends TestCase
{
    public function testConstruct(): void
    {
        $user = $this->createStub(UserInterface::class);
        $event = new DummyUserEvent($user);

        self::assertSame($user, $event->getUser());
    }
}

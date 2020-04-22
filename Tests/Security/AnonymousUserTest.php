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

namespace StfalconStudio\ApiBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Security\AnonymousUser;

final class AnonymousUserTest extends TestCase
{
    /** @var AnonymousUser */
    private $anonymousUser;

    protected function setUp(): void
    {
        $this->anonymousUser = new AnonymousUser();
    }

    protected function tearDown(): void
    {
        unset($this->anonymousUser);
    }

    public function testConstructor(): void
    {
        self::assertSame([], $this->anonymousUser->getRoles());
        self::assertNull($this->anonymousUser->getPassword());
        self::assertNull($this->anonymousUser->getSalt());
        self::assertSame('anonymous', $this->anonymousUser->getUsername());
        $this->anonymousUser->eraseCredentials();
    }
}

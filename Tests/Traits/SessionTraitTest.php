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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionTraitTest extends TestCase
{
    /** @var Session|MockObject */
    private $session;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->session
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setSession($this->session);
        self::assertSame($this->session, $this->dummyClass->getSession());
    }
}

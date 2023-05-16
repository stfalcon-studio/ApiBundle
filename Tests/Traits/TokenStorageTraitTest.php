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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TokenStorageTraitTest extends TestCase
{
    private TokenStorageInterface|MockObject $tokenStorage;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->tokenStorage
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setTokenStorage($this->tokenStorage);
        self::assertSame($this->tokenStorage, $this->dummyClass->getTokenStorage());
    }
}

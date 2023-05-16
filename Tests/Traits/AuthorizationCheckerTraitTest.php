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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AuthorizationCheckerTraitTest extends TestCase
{
    private AuthorizationCheckerInterface|MockObject $authenticationUtils;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->authenticationUtils = $this->createMock(AuthorizationCheckerInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->authenticationUtils
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setAuthorizationChecker($this->authenticationUtils);
        self::assertSame($this->authenticationUtils, $this->dummyClass->getAuthorizationChecker());
    }
}

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
use Twig\Environment;

final class TwigTraitTest extends TestCase
{
    private Environment|MockObject $twig;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->twig
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setTwigEnvironment($this->twig);
        self::assertSame($this->twig, $this->dummyClass->getTwig());
    }
}

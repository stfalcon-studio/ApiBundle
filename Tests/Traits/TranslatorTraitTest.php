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
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatorTraitTest extends TestCase
{
    /** @var TranslatorInterface|MockObject */
    private $translator;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->translator
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setTranslator($this->translator);
        self::assertSame($this->translator, $this->dummyClass->getTranslator());
    }
}

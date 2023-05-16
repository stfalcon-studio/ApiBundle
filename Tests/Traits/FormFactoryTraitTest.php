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
use Symfony\Component\Form\FormFactoryInterface;

final class FormFactoryTraitTest extends TestCase
{
    private FormFactoryInterface|MockObject $formFactory;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->formFactory,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setFormFactory($this->formFactory);
        self::assertSame($this->formFactory, $this->dummyClass->getFormFactory());
    }
}

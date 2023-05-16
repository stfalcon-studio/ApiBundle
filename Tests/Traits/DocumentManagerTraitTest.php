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

use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DocumentManagerTraitTest extends TestCase
{
    private DocumentManager|MockObject $documentManager;
    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->documentManager = $this->createMock(DocumentManager::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->documentManager,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setDocumentManager($this->documentManager);
        self::assertSame($this->documentManager, $this->dummyClass->getDocumentManager());
    }
}

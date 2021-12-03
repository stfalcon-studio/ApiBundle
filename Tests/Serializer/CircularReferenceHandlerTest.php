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

namespace StfalconStudio\ApiBundle\Tests\Serializer;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Model\UUID\UuidInterface;
use StfalconStudio\ApiBundle\Serializer\CircularReferenceHandler;

final class CircularReferenceHandlerTest extends TestCase
{
    public function testHandleById(): void
    {
        $handler = new CircularReferenceHandler();

        $object = $this
            ->getMockBuilder(UuidInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMock()
        ;

        $object
            ->expects(self::once())
            ->method('getId')
        ;

        $handler($object)(); // Execute callback
    }
}

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

namespace StfalconStudio\ApiBundle\Tests\Asset;

use Fresh\DateTime\DateTimeHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Asset\DatetimeVersionStrategy;

final class DatetimeVersionStrategyTest extends TestCase
{
    /** @var DateTimeHelper|MockObject */
    private DateTimeHelper|MockObject $dateTimeHelper;

    private DatetimeVersionStrategy $datetimeVersionStrategy;

    protected function setUp(): void
    {
        $this->dateTimeHelper = $this->createMock(DateTimeHelper::class);
        $this->dateTimeHelper
            ->expects(self::once())
            ->method('getCurrentDatetimeImmutable')
            ->willReturn(new \DateTimeImmutable('2030-01-01 12:34:56'))
        ;
        $this->datetimeVersionStrategy = new DatetimeVersionStrategy($this->dateTimeHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->dateTimeHelper,
            $this->datetimeVersionStrategy,
        );
    }

    public function testGetVersion(): void
    {
        self::assertSame('20300101123456', $this->datetimeVersionStrategy->getVersion('test'));
    }

    public function testApplyVersion(): void
    {
        self::assertSame('test?v=20300101123456', $this->datetimeVersionStrategy->applyVersion('test'));
    }
}

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

namespace StfalconStudio\ApiBundle\Tests\Command;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\Console\InvalidParameterException;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AbstractBaseCommandTest extends TestCase
{
    private OutputFormatterInterface|MockObject $formatter;
    private InputInterface|MockObject $input;
    private OutputInterface|MockObject $output;
    private DummyCommand $command;

    protected function setUp(): void
    {
        $this->formatter = $this->createMock(OutputFormatterInterface::class);
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->output->method('getFormatter')->willReturn($this->formatter);
        $this->command = new DummyCommand();
    }

    protected function tearDown(): void
    {
        unset(
            $this->formatter,
            $this->input,
            $this->output,
            $this->command
        );
    }

    public function testConfigure(): void
    {
        $this->command->runConfigure();
        self::assertTrue($this->command->getDefinition()->hasOption('current-date'));

        $option = $this->command->getDefinition()->getOption('current-date');
        self::assertSame('d', $option->getShortcut());
        self::assertTrue($option->isValueOptional());
        self::assertSame('now', $option->getDefault());
    }

    public function testInitializeWrongTypeOfDate(): void
    {
        $this->input
            ->expects(self::once())
            ->method('getOption')
            ->willReturn(123)
        ;

        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessage('Parameter `current-date` is not a string');

        $this->command->runInitialize($this->input, $this->output);
    }

    #[DataProvider('dataProviderForInitializeInvalidDateFormat')]
    public function testInitializeInvalidDateFormat(string $optionValue): void
    {
        $this->input
            ->expects(self::once())
            ->method('getOption')
            ->willReturn($optionValue)
        ;

        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessage('Invalid date format. Correct format YYYY-MM-DD, e.g. 2018-11-01');

        $this->command->runInitialize($this->input, $this->output);
    }

    public static function dataProviderForInitializeInvalidDateFormat(): iterable
    {
        yield ['wrong date format'];
        yield ['01.01.2019'];
    }

    public function testInitializeWithDefaultValue(): void
    {
        $this->input
            ->expects(self::once())
            ->method('getOption')
            ->willReturn('now')
        ;

        $this->command->runInitialize($this->input, $this->output);

        self::assertSame(
            (new \DateTime('now'))->format('Y-m-d H:i:s'),
            $this->command->getCurrentDate()->format('Y-m-d H:i:s')
        );
    }

    public function testInitializeWithCorrectValue(): void
    {
        $this->input
            ->expects(self::once())
            ->method('getOption')
            ->willReturn('2022-01-01')
        ;

        $this->command->runInitialize($this->input, $this->output);

        self::assertSame(
            (new \DateTime('2022-01-01'))->format('Y-m-d H:i:s'),
            $this->command->getCurrentDate()->format('Y-m-d H:i:s')
        );
    }
}

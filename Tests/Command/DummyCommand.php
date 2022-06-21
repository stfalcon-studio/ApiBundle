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

use StfalconStudio\ApiBundle\Command\AbstractBaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DummyCommand.
 */
final class DummyCommand extends AbstractBaseCommand
{
    public function runConfigure(): void
    {
        $this->configure();
    }

    public function runInitialize(InputInterface $input, OutputInterface $output): void
    {
        $this->initialize($input, $output);
    }

    public function getCurrentDate(): \DateTime
    {
        return $this->currentDate;
    }
}

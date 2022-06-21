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

namespace StfalconStudio\ApiBundle\Command;

use StfalconStudio\ApiBundle\Exception\Console\InvalidParameterException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractBaseCommand extends Command
{
    private const DEFAULT_CURRENT_DATE_VALUE = 'now';

    protected \DateTime $currentDate;

    protected function configure(): void
    {
        parent::configure();

        $this
            ->addOption('current-date', 'd', InputOption::VALUE_OPTIONAL, 'Date in format YYYY-MM-DD', self::DEFAULT_CURRENT_DATE_VALUE)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $io = new SymfonyStyle($input, $output);

        try {
            $currentDateFromInput = $input->getOption('current-date');

            if (!\is_string($currentDateFromInput)) {
                throw new InvalidParameterException('Parameter `current-date` is not a string');
            }

            if (null !== $currentDateFromInput && self::DEFAULT_CURRENT_DATE_VALUE !== $currentDateFromInput) {
                $date = \DateTime::createFromFormat('Y-m-d', $currentDateFromInput);
                if (false === $date) {
                    throw new InvalidParameterException('Invalid date format. Correct format YYYY-MM-DD, e.g. 2018-11-01');
                }

                $date->setTime(0, 0);
                $this->currentDate = $date;
            } else {
                $this->currentDate = (new \DateTime('now'))->setTime(0, 0);
            }
        } catch (InvalidParameterException $e) {
            $io->write($e->getMessage(), false, SymfonyStyle::OUTPUT_RAW);

            throw $e;
        }
    }
}

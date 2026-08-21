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

namespace StfalconStudio\ApiBundle\Command\JWT;

use StfalconStudio\ApiBundle\Command\AbstractBaseCommand;
use StfalconStudio\ApiBundle\Repository\JWT\RefreshTokenRepository;
use StfalconStudio\ApiBundle\Traits\EntityManagerTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'stfalcon-api-bundle:jwt:clear-refresh-token', description: 'Clear invalid refresh tokens.')]
class ClearInvalidRefreshTokensCommand extends AbstractBaseCommand
{
    use EntityManagerTrait;
    use LockableTrait;

    private const DEFAULT_BATCH_SIZE = 500;

    protected int $batchSize;

    public function __construct(private readonly RefreshTokenRepository $refreshTokenRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->addOption('sql-delete', 'sd', InputOption::VALUE_OPTIONAL, 'Sql delete', 'false')
            ->addOption('batch-size', 'bs', InputOption::VALUE_OPTIONAL, 'Batch size', self::DEFAULT_BATCH_SIZE);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $batchSizeFromInput = $input->getOption('batch-size');
        if (!is_numeric($batchSizeFromInput)) {
            $this->batchSize = self::DEFAULT_BATCH_SIZE;
        } else {
            $this->batchSize = (int) $batchSizeFromInput;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock(self::getDefaultName())) {
            $output->writeln('The command is already running in another process.');

            return self::SUCCESS;
        }

        $io = new SymfonyStyle($input, $output);
        $io->title('Clear expired refresh token...');

        $sqlDelete = 'true' === $input->getOption('sql-delete');

        $count = $this->refreshTokenRepository->invalidTokensCount($this->currentDate);
        $io->info('Total: '.$count);

        if ($count > 0) {
            $progressBar = $io->createProgressBar($count);

            if ($sqlDelete) {
                $this->delete($progressBar);
            } else {
                $this->remove($progressBar);
            }

            $progressBar->finish();
            $io->newLine(2);
        } else {
            $io->info('There were no invalid tokens to revoke.');
        }

        $this->release();

        $io->success('DONE');

        return self::SUCCESS;
    }

    private function delete(ProgressBar $progressBar): void
    {
        do {
            $deleted = $this->refreshTokenRepository->deleteInvalid($this->currentDate, $this->batchSize);
            $progressBar->advance($deleted);
        } while ($this->batchSize === $deleted);
    }

    private function remove(ProgressBar $progressBar): void
    {
        do {
            $invalidTokens = $this->refreshTokenRepository->findInvalid($this->currentDate, $this->batchSize, 0);
            $batchCount = \count($invalidTokens);

            foreach ($invalidTokens as $invalidToken) {
                $this->em->remove($invalidToken);
            }

            $this->em->flush();
            $this->em->clear();

            $progressBar->advance($batchCount);
        } while ($this->batchSize === $batchCount);
    }
}

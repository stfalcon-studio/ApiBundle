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

namespace StfalconStudio\ApiBundle\EventListener\ORM\Aggregate;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Fresh\DateTime\DateTimeHelper;
use StfalconStudio\ApiBundle\Model\ORM\Aggregate\AggregatePartInterface;
use StfalconStudio\ApiBundle\Model\ORM\Aggregate\AggregateRootInterface;

/**
 * AggregatePartListener.
 */
final class AggregatePartListener
{
    /** @var array<AggregateRootInterface> */
    private array $aggregateRoots = [];

    /**
     * @param DateTimeHelper $dateTimeHelper
     */
    public function __construct(
        private readonly DateTimeHelper $dateTimeHelper,
    ) {
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->processEntity($entity);
        }
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->processEntity($entity);
        }
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->processEntity($entity);
        }

        foreach ($this->aggregateRoots as $aggregateRoot) {
            if (!$uow->isScheduledForDelete($aggregateRoot)) {
                $aggregateRoot->setUpdatedAt($this->dateTimeHelper->getCurrentDatetime());
                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(\get_class($aggregateRoot)), $aggregateRoot);
            }
        }

        $this->aggregateRoots = [];
    }

    /**
     * @param object $entity
     */
    private function processEntity(object $entity): void
    {
        if ($entity instanceof AggregatePartInterface) {
            $aggregateRoot = $entity->getAggregateRoot();

            if (!\in_array($aggregateRoot, $this->aggregateRoots, true)) {
                $this->aggregateRoots[$aggregateRoot->getId()] = $aggregateRoot;
                $this->processEntity($aggregateRoot); // Bubble aggregate root to the top root
            }
        }
    }
}

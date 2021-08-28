<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Model\Aggregate;

/**
 * AggregatePartInterface.
 */
interface AggregatePartInterface
{
    /**
     * @return AggregateRootInterface
     */
    public function getAggregateRoot(): AggregateRootInterface;
}

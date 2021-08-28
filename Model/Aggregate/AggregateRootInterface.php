<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Model\Aggregate;

use App\Model\Timestampable\TimestampableInterface;
use App\Model\UUID\UuidInterface;

/**
 * AggregateRootInterface.
 */
interface AggregateRootInterface extends TimestampableInterface, UuidInterface
{
}

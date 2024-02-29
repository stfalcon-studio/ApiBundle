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

namespace StfalconStudio\ApiBundle\Model\ODM\Aggregate;

use StfalconStudio\ApiBundle\Model\ODM\Timestampable\TimestampableInterface;
use StfalconStudio\ApiBundle\Model\ODM\UUID\UuidInterface;

/**
 * AggregateRootInterface.
 */
interface AggregateRootInterface extends TimestampableInterface, UuidInterface
{
}

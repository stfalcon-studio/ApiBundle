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

namespace StfalconStudio\ApiBundle\Tests\DTO;

use StfalconStudio\ApiBundle\DTO\OptimisticLockInterface;
use StfalconStudio\ApiBundle\DTO\OptimisticLockTrait;

/**
 * OptimisticLockEntity.
 */
final class OptimisticLockEntity implements OptimisticLockInterface
{
    use OptimisticLockTrait;
}

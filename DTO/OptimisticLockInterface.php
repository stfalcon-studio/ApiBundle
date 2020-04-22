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

namespace StfalconStudio\ApiBundle\DTO;

/**
 * OptimisticLockInterface.
 */
interface OptimisticLockInterface
{
    /**
     * @return int
     */
    public function getEditVersion(): int;

    /**
     * @param int $editVersion
     *
     * @return self
     */
    public function setEditVersion(int $editVersion);
}

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

namespace StfalconStudio\ApiBundle\Service\Repository;

/**
 * Findable By Id Interface.
 */
interface FindableByIdInterface
{
    /**
     * @param string $id
     *
     * @return mixed
     */
    public function findOneById(string $id): mixed;
}

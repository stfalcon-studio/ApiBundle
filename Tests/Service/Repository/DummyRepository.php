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

namespace StfalconStudio\ApiBundle\Tests\Service\Repository;

use StfalconStudio\ApiBundle\Service\Repository\FindableByIdInterface;
use StfalconStudio\ApiBundle\Service\Repository\GettableOneByIdInterface;

class DummyRepository implements GettableOneByIdInterface, FindableByIdInterface
{
    public function findOneById(string $id): mixed
    {
        return null;
    }

    public function getOneById(string $id): mixed
    {
        return null;
    }
}

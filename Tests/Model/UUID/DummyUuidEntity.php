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

namespace StfalconStudio\ApiBundle\Tests\Model\UUID;

use StfalconStudio\ApiBundle\Model\UUID\UuidTrait;
use Symfony\Component\Uid\Uuid;

class DummyUuidEntity
{
    use UuidTrait;

    private Uuid|string $id;

    public function setId(Uuid|string $id): void
    {
        $this->id = $id;
    }
}

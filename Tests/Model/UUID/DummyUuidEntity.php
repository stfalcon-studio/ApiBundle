<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Tests\Model\UUID;

use StfalconStudio\ApiBundle\Model\UUID\UuidTrait;
use Symfony\Component\Uid\Uuid;

class DummyUuidEntity
{
    use UuidTrait;

    /** @var Uuid|string */
    private $id;

    /**
     * @param Uuid|string $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
}

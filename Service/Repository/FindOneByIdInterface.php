<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Service\Repository;

interface FindOneByIdInterface
{
    public function findOneById(string $id): mixed;
}

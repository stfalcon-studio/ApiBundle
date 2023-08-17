<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Service\Repository;

interface GetOneByIdInterface
{
    public function getOneById(string $id): mixed;
}

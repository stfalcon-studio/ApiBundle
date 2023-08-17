<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Service\Repository;

use StfalconStudio\ApiBundle\Traits\EntityManagerTrait;

class RepositoryService
{
    use EntityManagerTrait;

    public function getEntityById(string $id, string $class): mixed
    {
        $repository = $this->em->getRepository($class); // @phpstan-ignore-line

        if (!$repository instanceof GetOneByIdInterface) {
            return null;
        }

        return $repository->getOneById($id);
    }
}

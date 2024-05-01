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

use StfalconStudio\ApiBundle\Exception\LogicException;
use StfalconStudio\ApiBundle\Traits\EntityManagerTrait;

/**
 * RepositoryService.
 *
 * @deprecated
 */
class RepositoryService
{
    use EntityManagerTrait;

    /**
     * @param string $id
     * @param string $class
     *
     * @return mixed
     */
    public function getEntityById(string $id, string $class): mixed
    {
        $repository = $this->em->getRepository($class); // @phpstan-ignore-line

        if (!$repository instanceof GettableOneByIdInterface) {
            throw new LogicException(\sprintf('Repository %s should implements %s interface', $repository::class, GettableOneByIdInterface::class));
        }

        return $repository->getOneById($id);
    }

    /**
     * @param string $id
     * @param string $class
     *
     * @return mixed
     */
    public function findEntityById(string $id, string $class): mixed
    {
        $repository = $this->em->getRepository($class); // @phpstan-ignore-line

        if (!$repository instanceof FindableByIdInterface) {
            throw new LogicException(\sprintf('Repository %s should implements %s interface', $repository::class, FindableByIdInterface::class));
        }

        return $repository->findOneById($id);
    }
}

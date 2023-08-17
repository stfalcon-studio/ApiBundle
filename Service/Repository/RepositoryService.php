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

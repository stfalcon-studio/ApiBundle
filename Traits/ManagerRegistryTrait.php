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

namespace StfalconStudio\ApiBundle\Traits;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * ManagerRegistryTrait.
 */
trait ManagerRegistryTrait
{
    protected ManagerRegistry $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    #[Required]
    public function setManagerRegistry(ManagerRegistry $managerRegistry): void
    {
        $this->managerRegistry = $managerRegistry;
    }
}

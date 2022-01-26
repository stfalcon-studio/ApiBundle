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

use Doctrine\ORM\EntityManager;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * EntityManagerTrait.
 */
trait EntityManagerTrait
{
    protected EntityManager $em;

    /**
     * @param EntityManager $em
     */
    #[Required]
    public function setEntityManager(EntityManager $em): void
    {
        $this->em = $em;
    }

    /**
     * Reopen closed entity manager.
     */
    public function reopenClosedEntityManager(): void
    {
        if (!$this->em->isOpen()) {
            $this->em = EntityManager::create($this->em->getConnection(), $this->em->getConfiguration());
        }
    }
}

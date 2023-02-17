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

namespace StfalconStudio\ApiBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * OptimisticLockTrait.
 */
trait OptimisticLockTrait
{
    #[Assert\Sequentially(
        constraints: [
            new Assert\NotBlank(),
            new Assert\GreaterThan(value: 0),
        ],
    )]
    private int $editVersion;

    /**
     * @return int
     */
    public function getEditVersion(): int
    {
        return $this->editVersion;
    }

    /**
     * @param int $editVersion
     *
     * @return static
     */
    public function setEditVersion(int $editVersion): static
    {
        $this->editVersion = $editVersion;

        return $this;
    }
}

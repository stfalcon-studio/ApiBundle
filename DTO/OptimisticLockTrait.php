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
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Type("int")
     * @Assert\GreaterThan(0)
     */
    private $editVersion;

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
     * @return self
     */
    public function setEditVersion(int $editVersion): self
    {
        $this->editVersion = $editVersion;

        return $this;
    }
}

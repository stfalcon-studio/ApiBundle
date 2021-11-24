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

use StfalconStudio\ApiBundle\Validator\EntityValidator;

/**
 * EntityValidatorTrait.
 */
trait EntityValidatorTrait
{
    protected EntityValidator $entityValidator;

    /**
     * @param EntityValidator $entityValidator
     *
     * @required
     */
    public function setEntityValidator(EntityValidator $entityValidator): void
    {
        $this->entityValidator = $entityValidator;
    }
}

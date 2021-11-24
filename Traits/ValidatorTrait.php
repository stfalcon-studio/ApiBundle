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

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ValidatorTrait.
 */
trait ValidatorTrait
{
    protected ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     *
     * @required
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}

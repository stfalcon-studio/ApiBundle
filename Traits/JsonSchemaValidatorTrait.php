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

use StfalconStudio\ApiBundle\Validator\JsonSchemaValidator;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * JsonSchemaValidatorTrait.
 */
trait JsonSchemaValidatorTrait
{
    protected JsonSchemaValidator $jsonSchemaValidator;

    /**
     * @param JsonSchemaValidator $jsonSchemaValidator
     */
    #[Required]
    public function setJsonSchemaValidator(JsonSchemaValidator $jsonSchemaValidator): void
    {
        $this->jsonSchemaValidator = $jsonSchemaValidator;
    }
}

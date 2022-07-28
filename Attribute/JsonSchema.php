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

namespace StfalconStudio\ApiBundle\Attribute;

use Attribute;

/**
 * JsonSchema Attribute.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class JsonSchema
{
    /**
     * @param string $jsonSchemaName
     */
    public function __construct(private readonly string $jsonSchemaName)
    {
    }

    /**
     * @return string
     */
    public function getJsonSchemaName(): string
    {
        return $this->jsonSchemaName;
    }
}

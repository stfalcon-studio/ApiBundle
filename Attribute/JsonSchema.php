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
 * JsonSchema Annotation.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class JsonSchema
{
    private readonly string $jsonSchemaName;

    /**
     * @param string $jsonSchemaName
     */
    public function __construct(string $jsonSchemaName)
    {
        $this->jsonSchemaName = $jsonSchemaName;
    }

    /**
     * @return string
     */
    public function getJsonSchemaName(): string
    {
        return $this->jsonSchemaName;
    }
}

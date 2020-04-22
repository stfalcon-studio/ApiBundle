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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Serializer\Serializer;
use StfalconStudio\ApiBundle\Traits;

/**
 * DummyClass.
 */
final class DummyClass
{
    use Traits\DtoExtractorTrait;
    use Traits\SerializerTrait;

    public function getDtoExtractor(): DtoExtractor
    {
        return $this->dtoExtractor;
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}

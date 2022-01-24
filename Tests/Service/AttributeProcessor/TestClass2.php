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

namespace StfalconStudio\ApiBundle\Tests\Service\AttributeProcessor;

use StfalconStudio\ApiBundle\Attribute\DTO;
use StfalconStudio\ApiBundle\Tests\Attribute\DummyDto;

/**
 * TestClass2.
 */
#[DTO(class: DummyDto::class)]
#[DTO(class: DummyDto::class)]
class TestClass2
{
}

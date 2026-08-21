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

namespace StfalconStudio\ApiBundle\Tests\Request\Filter;

use StfalconStudio\ApiBundle\Request\Filter\FilterInterface;

class DummyFilterModel implements FilterInterface
{
    public function __construct(private readonly string $foo)
    {
    }

    public function getFoo()
    {
        return $this->foo;
    }
}

<?php

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

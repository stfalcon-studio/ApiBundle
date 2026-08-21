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

use StfalconStudio\ApiBundle\Request\Filter\FilterExtractorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class DummyFilterExtractor implements FilterExtractorInterface
{
    public function extractFilterFromRequest(Request $request): DummyFilterModel
    {
        return new DummyFilterModel('foo');
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return DummyFilterModel::class === $argument->getType();
    }
}

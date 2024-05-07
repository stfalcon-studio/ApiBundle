<?php

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

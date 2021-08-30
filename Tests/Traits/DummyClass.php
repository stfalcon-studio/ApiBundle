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

use Sentry\ClientInterface;
use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Serializer\Serializer;
use StfalconStudio\ApiBundle\Traits;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * DummyClass.
 */
final class DummyClass
{
    use Traits\DtoExtractorTrait;
    use Traits\SentryClientTrait;
    use Traits\SerializerTrait;
    use Traits\SymfonySerializerTrait;
    use Traits\TranslatorTrait;

    public function getDtoExtractor(): DtoExtractor
    {
        return $this->dtoExtractor;
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    public function getSentryClient(): ClientInterface
    {
        return $this->sentryClient;
    }

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}

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

use StfalconStudio\ApiBundle\Exception\RuntimeException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * SymfonySerializerTrait.
 */
trait SymfonySerializerTrait
{
    /** @var SerializerInterface|Serializer */
    protected SerializerInterface|Serializer $symfonySerializer;

    /**
     * @param SerializerInterface|Serializer $symfonySerializer
     */
    #[Required]
    public function setSymfonySerializer(SerializerInterface $symfonySerializer): void
    {
        $this->symfonySerializer = $symfonySerializer;
    }

    /**
     * @return Serializer
     */
    public function getSymfonySerializer(): Serializer
    {
        if (!$this->symfonySerializer instanceof Serializer) {
            throw new RuntimeException(sprintf('Serializer is not instance of %s', Serializer::class));
        }

        return $this->symfonySerializer;
    }
}

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

use App\Exception\RuntimeException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * SymfonySerializerTrait.
 */
trait SymfonySerializerTrait
{
    /** @var SerializerInterface|Serializer */
    protected SerializerInterface $symfonySerializer;

    /**
     * @param SerializerInterface|Serializer $symfonySerializer
     *
     * @required
     */
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

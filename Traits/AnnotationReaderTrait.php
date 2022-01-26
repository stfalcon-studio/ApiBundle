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

use Doctrine\Common\Annotations\Reader;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AnnotationReaderTrait.
 */
trait AnnotationReaderTrait
{
    protected Reader $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    #[Required]
    public function setAnnotationReader(Reader $annotationReader): void
    {
        $this->annotationReader = $annotationReader;
    }
}

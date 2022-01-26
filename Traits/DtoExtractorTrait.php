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

use StfalconStudio\ApiBundle\Request\DtoExtractor;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * DtoExtractorTrait.
 */
trait DtoExtractorTrait
{
    protected DtoExtractor $dtoExtractor;

    /**
     * @param DtoExtractor $dtoExtractor
     */
    #[Required]
    public function setDtoExtractor(DtoExtractor $dtoExtractor): void
    {
        $this->dtoExtractor = $dtoExtractor;
    }
}

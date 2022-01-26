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

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * HttpKernelTrait.
 */
trait HttpKernelTrait
{
    protected HttpKernelInterface $httpKernel;

    /**
     * @param HttpKernelInterface $httpKernel
     */
    #[Required]
    public function setHttpKernel(HttpKernelInterface $httpKernel): void
    {
        $this->httpKernel = $httpKernel;
    }
}

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

namespace StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor;

use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;

/**
 * CustomAppExceptionResponseProcessorInterface.
 */
interface CustomAppExceptionResponseProcessorInterface
{
    /**
     * @param CustomAppExceptionInterface $exception
     *
     * @return bool
     */
    public function supports(CustomAppExceptionInterface $exception): bool;

    /**
     * @param CustomAppExceptionInterface $exception
     *
     * @return mixed[]
     */
    public function processResponse(CustomAppExceptionInterface $exception): array;
}

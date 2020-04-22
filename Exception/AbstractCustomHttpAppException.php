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

namespace StfalconStudio\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * AbstractCustomHttpAppException.
 */
abstract class AbstractCustomHttpAppException extends HttpException implements CustomAppExceptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function loggable(): bool
    {
        return false;
    }
}

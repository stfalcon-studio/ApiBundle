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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Kernel;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DummyHttpException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(string $message = '', \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_FORBIDDEN, $message, $previous);
    }
}

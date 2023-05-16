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

use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;
use Symfony\Component\HttpFoundation\Response;

final class DummyCustomAppLoggableException extends AbstractCustomHttpAppException
{
    public function __construct(?string $message = null, \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous);
    }

    public function loggable(): bool
    {
        return true;
    }

    public function getErrorName(): string
    {
        return 'dummy';
    }
}

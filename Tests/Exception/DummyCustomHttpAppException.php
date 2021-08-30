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

namespace StfalconStudio\ApiBundle\Tests\Exception;

use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;

/**
 * DummyCustomHttpAppException.
 */
class DummyCustomHttpAppException extends AbstractCustomHttpAppException
{
    public function getErrorName(): string
    {
        return 'dummy';
    }
}

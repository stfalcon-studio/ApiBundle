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

namespace StfalconStudio\ApiBundle\Exception\Http\Json;

use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;
use Symfony\Component\HttpFoundation\Response;

/**
 * MalformedJsonException.
 */
class MalformedJsonException extends AbstractCustomHttpAppException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(string $message = '', \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorName(): string
    {
        return BaseErrorNames::MALFORMED_JSON;
    }
}

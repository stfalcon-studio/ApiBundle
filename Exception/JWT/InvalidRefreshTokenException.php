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

namespace StfalconStudio\ApiBundle\Exception\JWT;

use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\AbstractCustomHttpAppException;
use Symfony\Component\HttpFoundation\Response;

/**
 * InvalidRefreshTokenException.
 */
class InvalidRefreshTokenException extends AbstractCustomHttpAppException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(\Exception $previous = null)
    {
        parent::__construct(Response::HTTP_UNAUTHORIZED, 'invalid_refresh_token_exception_message', $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorName(): string
    {
        return BaseErrorNames::INVALID_REFRESH_TOKEN;
    }
}

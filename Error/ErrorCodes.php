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

namespace StfalconStudio\ApiBundle\Error;

use Symfony\Component\HttpFoundation\Response;

/**
 * ErrorCodes.
 */
class ErrorCodes
{
    // 409
    public const CONFLICT_TARGET_RESOURCE_UPDATE = 40900;

    // 500
    public const INTERNAL_SERVER_ERROR = 50000;

    private const ERROR_NAMES = [
        // 409
        self::CONFLICT_TARGET_RESOURCE_UPDATE => BaseErrorNames::CONFLICT_TARGET_RESOURCE_UPDATE,
        // 500
        self::INTERNAL_SERVER_ERROR => BaseErrorNames::INTERNAL_SERVER_ERROR,
    ];

    /**
     * @param int      $errorCode
     * @param int|null $statusCode
     *
     * @return string
     */
    public static function getErrorNameByErrorCodeAndStatusCode(int $errorCode, int $statusCode = null): string
    {
        if (\array_key_exists($errorCode, self::ERROR_NAMES)) {
            return self::ERROR_NAMES[$errorCode];
        }

        switch ($statusCode) {
            case Response::HTTP_NOT_FOUND:
                $result = 'resource_not_found';
                break;
            case Response::HTTP_BAD_REQUEST:
                $result = 'invalid_request';
                break;
            case Response::HTTP_FORBIDDEN:
                $result = 'access_denied';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $result = 'method_not_allowed';
                break;
            default:
                $result = 'Error code is not yet specified for this case. Please contact to developer about this case.';
        }

        return $result;
    }
}

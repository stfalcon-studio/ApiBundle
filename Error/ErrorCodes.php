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
    // 400
    public const MISSED_REQUIRED_HEADER = 40000;
    public const MALFORMED_JSON = 40001;
    public const INVALID_JSON_SCHEMA = 40002;
    public const INCORRECT_HEADER = 40003;

    // 401
    public const INVALID_REFRESH_TOKEN = 40100;

    // 402
    public const INVALID_PAYMENT = 40200;

    // 403
    public const ACCESS_DENIED = 40300;
    public const AUTHORIZED_USER = 40301;

    // 409
    public const CONFLICT_TARGET_RESOURCE_UPDATE = 40900;

    // 422
    public const INVALID_ENTITY = 42200;

    // 500
    public const INTERNAL_SERVER_ERROR = 50000;

    private const ERROR_NAMES = [
        // 400
        self::MISSED_REQUIRED_HEADER => ErrorNames::MISSED_REQUIRED_HEADER,
        self::MALFORMED_JSON => ErrorNames::MALFORMED_JSON,
        self::INVALID_JSON_SCHEMA => ErrorNames::INVALID_JSON_SCHEMA,
        self::INCORRECT_HEADER => ErrorNames::INCORRECT_HEADER,
        // 401
        self::INVALID_REFRESH_TOKEN => ErrorNames::INCORRECT_HEADER,
        // 402
        self::INVALID_PAYMENT => ErrorNames::INVALID_PAYMENT,
        // 403
        self::ACCESS_DENIED => ErrorNames::ACCESS_DENIED,
        self::AUTHORIZED_USER => ErrorNames::AUTHORIZED_USER,
        // 409
        self::CONFLICT_TARGET_RESOURCE_UPDATE => ErrorNames::CONFLICT_TARGET_RESOURCE_UPDATE,
        // 422
        self::INVALID_ENTITY => ErrorNames::INVALID_ENTITY,
        // 500
        self::INTERNAL_SERVER_ERROR => ErrorNames::INTERNAL_SERVER_ERROR,
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

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

/**
 * BaseErrorNames.
 */
class BaseErrorNames
{
    // 400
    public const MISSED_REQUIRED_HEADER = 'missed_required_header';
    public const MALFORMED_JSON = 'malformed_json';
    public const INVALID_JSON_SCHEMA = 'invalid_json_schema';
    public const INCORRECT_HEADER = 'incorrect_header';
    public const INVALID_REQUEST = 'invalid_request';

    // 401
    public const UNAUTHORISED_USER = 'unauthorised_user';
    public const INVALID_REFRESH_TOKEN = 'invalid_refresh_token';

    // 402
    public const INVALID_PAYMENT = 'invalid_payment';

    // 403
    public const ACCESS_DENIED = 'access_denied';
    public const AUTHORIZED_USER = 'authorized_user';

    // 404
    public const RESOURCE_NOT_FOUND = 'resource_not_found';

    // 405
    public const HTTP_METHOD_NOT_ALLOWED = 'method_not_allowed';

    // 409
    public const CONFLICT_TARGET_RESOURCE_UPDATE = 'conflict_target_resource_update';

    // 422
    public const INVALID_ENTITY = 'invalid_entity';

    // 500
    public const INTERNAL_SERVER_ERROR = 'internal_server_error';

    /**
     * Constructor.
     */
    private function __construct()
    {
        // noop
    }
}

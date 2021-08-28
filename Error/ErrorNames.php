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
 * ErrorNames.
 */
class ErrorNames
{
    // 400
    public const MISSED_REQUIRED_HEADER = 'missed_required_header';
    public const MALFORMED_JSON = 'malformed_json';
    public const INVALID_JSON_SCHEMA = 'invalid_json_schema';
    public const INCORRECT_HEADER = 'incorrect_header';

    // 401
    public const UNAUTHORISED_USER = 'unauthorised_user';
    public const INVALID_REFRESH_TOKEN = 'invalid_refresh_token';

    // 402
    public const INVALID_PAYMENT = 'invalid_payment';

    // 403
    public const ACCESS_DENIED = 'access_denied';
    public const AUTHORIZED_USER = 'authorised_user';

    // 409
    public const CONFLICT_TARGET_RESOURCE_UPDATE = 'conflict_target_resource_update';

    // 422
    public const INVALID_ENTITY = 'invalid_entity';

    // 500
    public const INTERNAL_SERVER_ERROR = 'internal_server_error';
}

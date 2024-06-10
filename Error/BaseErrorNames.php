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
    public final const MISSED_REQUIRED_HEADER = 'missed_required_header';
    public final const MALFORMED_JSON = 'malformed_json';
    public final const INVALID_JSON_SCHEMA = 'invalid_json_schema';
    public final const INCORRECT_HEADER = 'incorrect_header';
    public final const INVALID_REQUEST = 'invalid_request';

    // 401
    public final const UNAUTHORISED_USER = 'unauthorised_user';
    public final const INVALID_REFRESH_TOKEN = 'invalid_refresh_token';

    // 402
    public final const INVALID_PAYMENT = 'invalid_payment';

    // 403
    public final const ACCESS_DENIED = 'access_denied';
    public final const AUTHORIZED_USER = 'authorized_user';

    // 404
    public final const RESOURCE_NOT_FOUND = 'resource_not_found';

    // 405
    public final const METHOD_NOT_ALLOWED = 'method_not_allowed';

    // 406
    public final const NOT_ACCEPTABLE = 'not_acceptable';

    // 409
    public final const CONFLICT_TARGET_RESOURCE_UPDATE = 'conflict_target_resource_update';

    // 422
    public final const INVALID_ENTITY = 'invalid_entity';

    // 429
    public final const HTTP_TOO_MANY_REQUESTS = 'too_many_requests';

    // 500
    public final const INTERNAL_SERVER_ERROR = 'internal_server_error';

    /**
     * Constructor.
     */
    private function __construct()
    {
        // noop
    }
}

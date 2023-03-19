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

namespace StfalconStudio\ApiBundle\Service\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ExceptionResponseFactory.
 */
class ExceptionResponseFactory
{
    /**
     * @param string $json
     * @param int    $statusCode
     * @param array  $headers
     *
     * @return JsonResponse
     */
    public function createJsonResponse(string $json, int $statusCode, array $headers): JsonResponse
    {
        return (new JsonResponse($json, $statusCode, $headers, true))->setEncodingOptions(\JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
    }
}

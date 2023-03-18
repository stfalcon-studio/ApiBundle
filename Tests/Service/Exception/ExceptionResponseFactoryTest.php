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

namespace StfalconStudio\ApiBundle\Tests\Service\Exception;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Service\Exception\ExceptionResponseFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * ExceptionResponseFactoryTest.
 */
final class ExceptionResponseFactoryTest extends TestCase
{
    public function testCreateJsonResponse(): void
    {
        $json = '{"test":"data"}';
        $statusCode = Response::HTTP_TOO_MANY_REQUESTS;
        $headers = ['Retry-After' => 60];

        $exceptionResponseFactory = new ExceptionResponseFactory();
        $response = $exceptionResponseFactory->createJsonResponse($json, $statusCode, $headers);

        self::assertSame($json, $response->getContent());
        self::assertSame($statusCode, $response->getStatusCode());
        self::assertSame('60', $response->headers->get('Retry-After'));
        self::assertSame(\JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE, $response->getEncodingOptions());
    }
}

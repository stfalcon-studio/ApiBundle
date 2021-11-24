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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpClientTraitTest extends TestCase
{
    /** @var HttpClientInterface|MockObject */
    private $httpClient;

    private DummyClass $dummyClass;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->dummyClass = new DummyClass();
    }

    protected function tearDown(): void
    {
        unset(
            $this->dummyClass,
            $this->httpClient,
        );
    }

    public function testSetter(): void
    {
        $this->dummyClass->setHttpClient($this->httpClient);
        self::assertSame($this->httpClient, $this->dummyClass->getHttpClient());
    }
}

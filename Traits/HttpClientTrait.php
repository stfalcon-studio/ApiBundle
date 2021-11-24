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

namespace StfalconStudio\ApiBundle\Traits;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * HttpClientTrait.
 */
trait HttpClientTrait
{
    protected HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     *
     * @required
     */
    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }
}

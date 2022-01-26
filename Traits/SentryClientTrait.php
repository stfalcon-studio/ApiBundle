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

use Sentry\ClientInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * SentryClientTrait.
 */
trait SentryClientTrait
{
    protected ClientInterface $sentryClient;

    /**
     * @param ClientInterface $sentryClient
     */
    #[Required]
    public function setSentryClient(ClientInterface $sentryClient): void
    {
        $this->sentryClient = $sentryClient;
    }
}

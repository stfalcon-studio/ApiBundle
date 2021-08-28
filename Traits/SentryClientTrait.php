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

use Sentry\FlushableClientInterface;

/**
 * SentryClientTrait.
 */
trait SentryClientTrait
{
    protected FlushableClientInterface $sentryClient;

    /**
     * @param FlushableClientInterface $sentryClient
     *
     * @required
     */
    public function setSentryClient(FlushableClientInterface $sentryClient): void
    {
        $this->sentryClient = $sentryClient;
    }
}

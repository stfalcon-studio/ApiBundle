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

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * MessageBusTrait.
 */
trait MessageBusTrait
{
    protected MessageBusInterface $bus;

    /**
     * @param MessageBusInterface $bus
     */
    #[Required]
    public function setBus(MessageBusInterface $bus): void
    {
        $this->bus = $bus;
    }
}

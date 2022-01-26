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

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * RouterTrait.
 */
trait RouterTrait
{
    protected RouterInterface $router;

    /**
     * @param RouterInterface $router
     */
    #[Required]
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}

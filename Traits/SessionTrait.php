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

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * SessionTrait.
 */
trait SessionTrait
{
    protected Session $session;

    /**
     * @param Session $session
     */
    #[Required]
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }
}

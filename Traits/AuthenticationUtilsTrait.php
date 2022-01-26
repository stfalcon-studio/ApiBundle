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

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AuthenticationUtilsTrait.
 */
trait AuthenticationUtilsTrait
{
    protected AuthenticationUtils $authenticationUtils;

    /**
     * @param AuthenticationUtils $utils
     */
    #[Required]
    public function setAuthenticationUtils(AuthenticationUtils $utils): void
    {
        $this->authenticationUtils = $utils;
    }
}

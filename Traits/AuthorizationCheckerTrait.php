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

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AuthorizationCheckerTrait.
 */
trait AuthorizationCheckerTrait
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    #[Required]
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }
}

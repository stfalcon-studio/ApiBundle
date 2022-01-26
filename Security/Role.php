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

namespace StfalconStudio\ApiBundle\Security;

/**
 * Role.
 */
class Role
{
    public final const ANONYMOUS = 'ROLE_ANONYMOUS';
    public final const USER = 'ROLE_USER';
}

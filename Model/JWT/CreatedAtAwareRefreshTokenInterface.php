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

namespace StfalconStudio\ApiBundle\Model\JWT;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;

/**
 * CreatedAtAwareRefreshTokenInterface.
 *
 * A refresh token that knows when it was issued.
 *
 * The creation date should not be derived from RefreshTokenInterface::getValid(),
 * because the expiration date is rewritten on every use when the `ttl_update` option is enabled
 */
interface CreatedAtAwareRefreshTokenInterface extends RefreshTokenInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;
}

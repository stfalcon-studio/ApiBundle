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

namespace StfalconStudio\ApiBundle\Model\Credentials;

/**
 * CredentialsInterface.
 */
interface CredentialsInterface
{
    /**
     * @param \DateTime $credentialsLastChangedAt
     *
     * @return self
     */
    public function setCredentialsLastChangedAt(\DateTime $credentialsLastChangedAt);

    /**
     * @return \DateTime|null
     */
    public function getCredentialsLastChangedAt(): ?\DateTime;
}

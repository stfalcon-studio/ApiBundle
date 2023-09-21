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

namespace StfalconStudio\ApiBundle\Model\ODM\Credentials;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * CredentialsTrait.
 */
trait CredentialsTrait
{
    #[MongoDB\Field(type: 'date', nullable: true)]
    protected ?\DateTimeInterface $credentialsLastChangedAt = null;

    /**
     * @param \DateTimeInterface|null $credentialsLastChangedAt
     *
     * @return self
     */
    public function setCredentialsLastChangedAt(?\DateTimeInterface $credentialsLastChangedAt): self
    {
        $this->credentialsLastChangedAt = $credentialsLastChangedAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCredentialsLastChangedAt(): ?\DateTimeInterface
    {
        return $this->credentialsLastChangedAt;
    }
}

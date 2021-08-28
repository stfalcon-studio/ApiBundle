<?php

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Model\Credentials;

use Doctrine\ORM\Mapping as ORM;

/**
 * CredentialsTrait.
 */
trait CredentialsTrait
{
    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    protected ?\DateTime $credentialsLastChangedAt = null;

    /**
     * @param \DateTime $credentialsLastChangedAt
     *
     * @return self
     */
    public function setCredentialsLastChangedAt(\DateTime $credentialsLastChangedAt): self
    {
        $this->credentialsLastChangedAt = $credentialsLastChangedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCredentialsLastChangedAt(): ?\DateTime
    {
        return $this->credentialsLastChangedAt;
    }
}

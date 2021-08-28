<?php

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

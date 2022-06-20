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

namespace StfalconStudio\ApiBundle\Tests\EventListener\JWT;

use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DummyUser implements UserInterface, CredentialsInterface
{
    public function setCredentialsLastChangedAt(?\DateTime $credentialsLastChangedAt): void
    {
    }

    public function getCredentialsLastChangedAt(): ?\DateTime
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return 'dummy';
    }
}

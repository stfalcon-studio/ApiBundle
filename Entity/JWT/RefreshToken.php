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

namespace StfalconStudio\ApiBundle\Entity\JWT;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RefreshToken.
 *
 * @ORM\Entity(repositoryClass="Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository")
 * @ORM\Table(
 *     name="jwt_refresh_tokens",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_refresh_token", columns={"refresh_token"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"refresh_token"}),
 *     }
 * )
 */
class RefreshToken extends AbstractRefreshToken
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="refresh_token", type="string", unique=true, length=128)
     */
    protected $refreshToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime")
     */
    protected $valid;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     *
     * @Assert\Type("\DateTimeImmutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

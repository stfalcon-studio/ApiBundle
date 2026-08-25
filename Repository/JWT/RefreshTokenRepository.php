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

namespace StfalconStudio\ApiBundle\Repository\JWT;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;
use StfalconStudio\ApiBundle\Entity\JWT\RefreshToken;
use StfalconStudio\ApiBundle\Exception\UnexpectedValueException;

/**
 * RefreshTokenRepository.
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RefreshToken::class);
    }

    /**
     * @param \DateTimeInterface|null $datetime
     *
     * @return RefreshToken[]
     */
    public function findInvalid($datetime = null): array
    {
        $datetime = (null === $datetime) ? new \DateTime() : $datetime;

        /** @var RefreshToken[] $result */
        $result = $this->createQueryBuilder('u')
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param \DateTimeInterface $datetime
     * @param int                $limit
     * @param int                $offset
     *
     * @return RefreshToken[]
     */
    public function findInvalidWithPagination(\DateTimeInterface $datetime, int $limit, int $offset = 0): array
    {
        /** @var RefreshToken[] $result */
        $result = $this->createQueryBuilder('u')
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param \DateTimeInterface $datetime
     *
     * @return int
     */
    public function invalidTokensCount(\DateTimeInterface $datetime): int
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select($qb->expr()->count('u.id'))
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
        ;

        $result = $qb->getQuery()->getSingleScalarResult();

        if (!\is_int($result) && !\is_string($result)) {
            throw new UnexpectedValueException('Value is not int, nor string');
        }

        return (int) $result;
    }

    /**
     * @param \DateTimeInterface $datetime
     * @param int                $limit
     *
     * @return int
     */
    public function deleteInvalid(\DateTimeInterface $datetime, int $limit): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            DELETE FROM jwt_refresh_tokens
            WHERE id IN (
                SELECT id FROM jwt_refresh_tokens
                WHERE valid < :datetime
                LIMIT :limit
            )
        ';

        return (int) $conn->executeStatement(
            $sql,
            [
                'datetime' => $datetime->format('Y-m-d H:i:s'),
                'limit' => $limit,
            ],
            [
                'datetime' => \PDO::PARAM_STR,
                'limit' => \PDO::PARAM_INT,
            ]
        );
    }
}

<?php

namespace App\Repository;

use App\Entity\BlackListApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlackListApplication>
 */
class BlackListApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlackListApplication::class);
    }

    public function isPassportBlacklisted(string $passportNumber): bool
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.passportNumber = :p')
            ->setParameter('p', $passportNumber);

        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }
}

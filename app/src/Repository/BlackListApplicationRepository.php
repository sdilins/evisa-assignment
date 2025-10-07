<?php

namespace App\Repository;

use App\Entity\Application;
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

    public function findOneByPassport(string $passportNumber): ?BlackListApplication
    {
        return $this->findOneBy(['passportNumber' => $passportNumber]);
    }
}

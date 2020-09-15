<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Happyr\NormalDistributionBundle\Entity\Summary;

final class SummaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Summary::class);
    }

    public function findOneByName(string $name): ?Summary
    {
        return $this->findOneBy(['name' => $name]);
    }
}

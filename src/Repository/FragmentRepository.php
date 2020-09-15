<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Happyr\NormalDistributionBundle\Entity\Fragment;

final class FragmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fragment::class);
    }

    /**
     * @return Fragment[]
     */
    public function findBySummary(int $summaryId)
    {
        return $this->findBy(['summary' => $summaryId]);
    }
}

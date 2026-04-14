<?php

namespace App\Repository;

use App\Entity\Department;
use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function byRegionOrderedByCode(Region $region): array
    {
        return $this->createQueryBuilder('department')
            ->leftJoin('department.region', 'region')
            ->andWhere('region.code = :code')
            ->orderBy('department.code', 'ASC')
            ->setParameter('code', $region->getCode())
            ->getQuery()
            ->getResult();
    }
}

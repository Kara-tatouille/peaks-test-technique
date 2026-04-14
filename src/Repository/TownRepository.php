<?php

namespace App\Repository;

use App\Entity\Department;
use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Town>
 */
class TownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Town::class);
    }

    public function byDepartmentOrderedByNameQuery(Department $department):Query
    {
        return $this->createQueryBuilder('town')
            ->leftJoin('town.department', 'department')
            ->where('department.code = :code')
            ->setParameter('code', $department->getCode())
            ->orderBy('town.name', 'ASC')
            ->getQuery();
    }

    public function byName(string $search): ?Town
    {
        return $this->createQueryBuilder('town')
            ->where('LOWER(town.name) LIKE :search')
            ->setParameter('search', strtolower("{$search}%"))
            ->orderBy('town.name', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

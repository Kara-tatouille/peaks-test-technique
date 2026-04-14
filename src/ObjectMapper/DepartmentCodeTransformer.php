<?php

namespace App\ObjectMapper;

use App\Entity\Department;
use App\Entity\Region;
use App\Model\DepartmentInput;
use App\Repository\DepartmentRepository;
use App\Repository\RegionRepository;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

readonly class DepartmentCodeTransformer implements TransformCallableInterface
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
    ){}

    public function __invoke(mixed $value, object $source, ?object $target): Department
    {
        $departmentEntity = $this->departmentRepository->findOneBy(['code' => $value]);
        if (!$departmentEntity) {
            throw new \InvalidArgumentException("Department '$value' does not exist in database.");
        }

        return $departmentEntity;
    }
}

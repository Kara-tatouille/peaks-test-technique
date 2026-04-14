<?php

namespace App\ObjectMapper;

use App\Entity\Department;
use App\Entity\Region;
use App\Model\DepartmentInput;
use App\Repository\RegionRepository;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

readonly class RegionCodeTransformer implements TransformCallableInterface
{
    public function __construct(
        private RegionRepository $regionRepository,
    ){}

    public function __invoke(mixed $value, object $source, ?object $target): Region
    {
        $regionEntity = $this->regionRepository->findOneBy(['code' => $value]);
        if (!$regionEntity) {
            throw new \InvalidArgumentException("Region '$value' does not exist in database.");
        }

        return $regionEntity;
    }
}

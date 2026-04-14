<?php

namespace App\Model;

use App\Entity\Town;
use App\ObjectMapper\DepartmentCodeTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Serializer\Attribute\SerializedName;

class TownDetailsInput
{
    #[SerializedName('codesPostaux')]
    public array $postalCodes;
    #[SerializedName('nom')]
    public string $name;
    public string $code;
    #[SerializedName('codeDepartement')]
    #[Map(target: 'department', transform: DepartmentCodeTransformer::class)]
    public string $departmentCode;
    #[SerializedName('surface')]
    public float $area;
    public int $population;
}

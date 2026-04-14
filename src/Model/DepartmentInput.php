<?php

namespace App\Model;

use App\Entity\Department;
use App\ObjectMapper\RegionCodeTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[Map(target: Department::class)]
class DepartmentInput
{
    #[SerializedName('nom')]
    public string $name;
    public string $code;
    #[Map(target: 'region', transform: RegionCodeTransformer::class)]
    #[SerializedName('codeRegion')]
    public string $regionCode;
}

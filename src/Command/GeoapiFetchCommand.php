<?php

namespace App\Command;

use App\Entity\Department;
use App\Model\DepartmentInput;
use App\Repository\DepartmentRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Component\HttpClient\UriTemplateHttpClient;
use Symfony\Component\ObjectMapper\ObjectMapper;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Region;

#[AsCommand(
    name: 'app:geoapi:fetch',
    description: 'Add a short description for your command',
)]
readonly class GeoapiFetchCommand
{
    public function __construct(
        private EntityManagerInterface $em,
        private RegionRepository $regionRepository,
        private DepartmentRepository $departmentRepository,
        private HttpClientInterface $geoapiClient,
        private SerializerInterface $serializer,
        private ObjectMapperInterface $objectMapper,
    )
    {
    }

    public function __invoke(): int
    {
        $this->populateRegions();

        $this->populateDepartments();

        $this->populateTowns();

        return Command::SUCCESS;
    }

    private function populateRegions(): void
    {
        $response = $this->geoapiClient->request('GET', '/regions');

        /** @var Region[] $regions */
        $regions = $this->serializer->deserialize($response->getContent(), Region::class . '[]', 'json');
        foreach ($regions as $region) {
            if ($exist = $this->regionRepository->findOneBy(['code' => $region->getCode()])) {
                $exist->setName($region->getName());
            } else {
                $this->em->persist($region);
            }
        }
        $this->em->flush();
    }

    private function populateDepartments(): void
    {
        $response = $this->geoapiClient->request('GET', '/departements');

        /** @var Department[] $departmentInputs */
        $departmentInputs = $this->serializer->deserialize(
            $response->getContent(),
            DepartmentInput::class . '[]',
            'json',
        );
        foreach ($departmentInputs as $departmentInput) {
            $department = $this->objectMapper->map($departmentInput);

            if ($exist = $this->departmentRepository->findOneBy(['code' => $department->getCode()])) {
                $exist->setName($department->getName());
                $exist->setRegion($department->getRegion());
            } else {
                $this->em->persist($department);
            }
        }
        $this->em->flush();
    }

    private function populateTowns()
    {

    }
}

<?php

namespace App\Command;

use App\Model\TownInput;
use App\Repository\TownRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:geoapi:towns',
    description: 'Add a short description for your command',
)]
readonly class GeoapiTownsCommand
{
    public function __construct(
        private EntityManagerInterface $em,
        private TownRepository $townRepository,
        private HttpClientInterface $geoapiClient,
        private SerializerInterface $serializer,
        private ObjectMapperInterface $objectMapper,

    ){}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('the department code from witch the towns will be fetched')] string $departmentCode
    ): int {
        return $this->populateTowns($io, $departmentCode);
    }

    private function populateTowns(SymfonyStyle $io, string $departmentCode): int
    {
        $io->comment('starting towns');
        $response = $this->geoapiClient->request('GET', "/departements/{$departmentCode}/communes");
        try {
            $json = $response->getContent();
        } catch (ClientExceptionInterface $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
        $townInputs = $this->serializer->deserialize(
            $json,
            TownInput::class.'[]',
            'json',
        );

        foreach ($townInputs as $i => $townInput) {
            $town = $this->objectMapper->map($townInput);

            if ($exist = $this->townRepository->findOneBy(['code' => $town->getCode()])) {
                $exist->setName($town->getName());
                $exist->setCode($town->getCode());
                $exist->setDepartment($town->getDepartment());
                $exist->setPostalCodes($town->getPostalCodes());
            } else {
                $this->em->persist($town);
            }

        }
        $this->em->flush();
        $this->em->clear();
        $io->success('finished towns');

        return Command::SUCCESS;
    }

}

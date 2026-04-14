<?php

namespace App\Service;

use App\Entity\Town;
use App\Model\TownDetailsInput;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class TownDetailFetcher
{

    public function __construct(
        private HttpClientInterface $geoapiClient,
        private SerializerInterface $serializer,
    ){}

    public function fetch(Town $town): TownDetailsInput
    {
        $response = $this->geoapiClient->request('GET', '/communes/'. $town->getCode(), ['query' => [
            'fields' => 'code,nom,population,codesPostaux,centre,surface',
        ]]);

        return $this->serializer->deserialize($response->getContent(), TownDetailsInput::class, 'json');
    }
}

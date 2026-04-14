<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Region;
use App\Entity\Town;
use App\Repository\TownRepository;
use App\Service\TownDetailFetcher;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;

final class TownController extends AbstractController
{
    #[Route('region/{region}/departments/{department}/communes', name: 'app_town')]
    public function index(
        #[MapEntity(mapping: ['region' => 'code'])] Region $region,
        #[MapEntity(mapping: ['department' => 'code'])] Department $department,
        TownRepository $townRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10,
    ): Response {
        $pagination = $paginator->paginate(
            $townRepository->byDepartmentOrderedByNameQuery($department),
            $page,
            $limit,
        );

        return $this->render('town/index.html.twig', [
            'region' => $region,
            'department' => $department,
            'pagination' => $pagination,
            'limit' => $limit,
            'page' => $page,
        ]);
    }

    #[Route('region/{region}/departments/{department}/communes/{town}', name: 'app_town_details')]
    public function show(
        #[MapEntity(mapping: ['region' => 'code'])] Region $region,
        #[MapEntity(mapping: ['department' => 'code'])] Department $department,
        #[MapEntity(mapping: ['town' => 'name'])] Town $town,
        TownDetailFetcher $townDetailFetcher,
    ): Response{
        $cache = new FilesystemAdapter();
        $townDetails = $cache->get('town_details', function (ItemInterface $item) use ($townDetailFetcher, $town) {
            $item->expiresAfter(new \DateInterval('P1D')); // one day

            return $townDetailFetcher->fetch($town);
        });

        return $this->render('town/show.html.twig', [
            'region' => $region,
            'department' => $department,
            'town' => $townDetails,
        ]);
    }
}

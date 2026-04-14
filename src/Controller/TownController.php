<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Region;
use App\Repository\TownRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

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
            'department' => $department,
            'pagination' => $pagination,
            'limit' => $limit,
            'page' => $page,
        ]);
    }
}

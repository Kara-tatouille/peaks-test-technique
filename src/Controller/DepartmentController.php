<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartmentController extends AbstractController
{
    #[Route('region/{code:region}/departments', name: 'app_department')]
    public function index(Region $region, DepartmentRepository $departmentRepository): Response
    {

        return $this->render('department/index.html.twig', [
            'departments' => $departmentRepository->byRegionOrderedByCode($region),
        ]);
    }
}

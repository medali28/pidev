<?php

namespace App\Controller;

use App\Entity\Cnam;
use App\Entity\Consultation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $consultationRepository = $this->getDoctrine()->getRepository(Consultation::class);
        $consultations = $consultationRepository->findAll();

        $cnamRepository = $this->getDoctrine()->getRepository(Cnam::class);
        $cnams = $cnamRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'consultations' => $consultations,
            'cnams' => $cnams,
        ]);
    }
}

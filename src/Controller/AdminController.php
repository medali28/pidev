<?php

namespace App\Controller;

use App\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {   $RendezVousRepository = $this->getDoctrine()->getRepository(RendezVous::class);
        $rendezVousData =$RendezVousRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'rendez_vouses' => $rendezVousData,
        ]);
    }
}

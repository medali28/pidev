<?php

namespace App\Controller;

use App\Entity\Ambulance;
use App\Entity\RendezVous;
use App\Form\AmbulanceType;
use App\Repository\AmbulanceRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ambulance')]
class AmbulanceController extends AbstractController
{
    #[Route('/', name: 'app_ambulance_index', methods: ['GET'])]
    public function index(AmbulanceRepository $ambulanceRepository): Response
    {
        return $this->render('ambulance/index.html.twig', [
            'ambulances' => $ambulanceRepository->findAll(),
        ]);
    }

    #[Route('/new/{rendezVous}', name: 'app_ambulance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id,RendezVousRepository $rendezVousRepository): Response
    {
        $ambulance = new Ambulance();

        $rendezVous= $rendezVousRepository->findRendezVousById($id);
        $ambulance->setRdv($rendezVous);
        $form = $this->createForm(AmbulanceType::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ambulance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambulance/new.html.twig', [
            'ambulance' => $ambulance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambulance_show', methods: ['GET'])]
    public function show(Ambulance $ambulance): Response
    {
        return $this->render('ambulance/show.html.twig', [
            'ambulance' => $ambulance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ambulance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmbulanceType::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambulance/edit.html.twig', [
            'ambulance' => $ambulance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambulance_delete', methods: ['POST'])]
    public function delete(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ambulance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ambulance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
    }
}

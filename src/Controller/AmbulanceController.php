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
// framework/index.php



#[Route('/ambulance')]
class AmbulanceController extends AbstractController
{


    #[Route('/', name: 'app_ambulance_index', methods: ['GET'])]
    public function index(AmbulanceRepository $ambulanceRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
                return $this->render('ambulance/index.html.twig', [
                    'ambulances' => $ambulanceRepository->findAll(),
                ]);
            }} return $this->redirectToRoute('app_login');
    }

    #[Route('/new/{id}', name: 'app_ambulance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RendezVous $rendezVous): Response
    {if ($this->getUser()) {
        if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
        $ambulance = new Ambulance();
        $ambulance->setRdv($rendezVous);
        $ambulance->setLocalActuelPatient(" ");
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $besoin_infirmier = $request->request->get('besoin_infirmier');
        $besoin_infirmier = $besoin_infirmier === 'on';
        $ambulance->setLatitude($latitude);
        $ambulance->setLongitude($longitude);
        $ambulance->setBesoinInfirmier($besoin_infirmier);

        if ($request->isMethod('POST')){
            $entityManager->persist($ambulance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('ambulance/new.html.twig', [
            'ambulance' => $ambulance,
            'id' => $rendezVous->getId(),
        ]);
    }}
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}', name: 'app_ambulance_show', methods: ['GET'])]
    public function show(Ambulance $ambulance): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
        return $this->render('ambulance/show.html.twig', [
            'ambulance' => $ambulance,
        ]);
    }}
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/edit', name: 'app_ambulance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
        $form = $this->createForm(AmbulanceType::class, $ambulance);
        $form->handleRequest($request);
        // Handle form submission
        if ($request->isMethod('POST')) {
            $ambulance->setLocalActuelPatient(" ");
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');
            $besoin_infirmier = $request->request->get('besoin_infirmier');
            $besoin_infirmier = $besoin_infirmier === 'on';
            $ambulance->setLatitude($latitude);
            $ambulance->setLongitude($longitude);
            $ambulance->setBesoinInfirmier($besoin_infirmier);
            $entityManager->persist($ambulance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }

        // Render the form
        return $this->render('ambulance/edit.html.twig', [
            'ambulance' => $ambulance,
        ]);
    }}
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}', name: 'app_ambulance_delete', methods: ['POST'])]
    public function delete(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {if ($this->getUser()) {
        if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
            if ($this->isCsrfTokenValid('delete' . $ambulance->getId(), $request->request->get('_token'))) {
                $entityManager->remove($ambulance);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }
    }
        return $this->redirectToRoute('app_login');
    }
}

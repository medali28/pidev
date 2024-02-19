<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\RendezVous;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/consultation')]
class ConsultationController extends AbstractController
{
    #[Route('/', name: 'app_consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $RendezVousRepository=$this->getDoctrine()->getRepository(RendezVous::class);
        $RendezVous = $RendezVousRepository->findRendezVousById(15);

        /*$consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($consultation);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);*/
        // Créer une nouvelle instance de Consultation
        $consultation = new Consultation();
// Récupération des données du formulaire
        if ($request->isMethod('POST')) {
            $descriptionMaladie = $request->request->get('Description');
            // $dureeMaladie = $request->request->get('Duré_de_maladie');
            $poids = $request->request->get('Poids_de_patient');
            $taille = $request->request->get('taille');
            $temperature = $request->request->get('temperature');
            $frequenceCardiaque = $request->request->get('frequence-cardiaque');
            $respiration = $request->request->get('respiration');
            $conseilsMaladie = $request->request->get('Conseils_de_maladie');
            $nomMedicament = $request->request->get('nomMedicament');
            $dateConsultation = new \DateTime($request->request->get('date-consultation'));

            // Setters pour définir les valeurs dans l'entité RendezVous
            $consultation->setRdv($RendezVous);
            if ($descriptionMaladie !== null) {
                $consultation->setDescription($descriptionMaladie);
            }else
            {
                $consultation->setDescription("null");
            }
            // $consultation->setDureeMaladie($dureeMaladie);
            $consultation->setPoids(floatval($poids));
            $consultation->setTaille(floatval($taille));
            $consultation->setTemperature(floatval($temperature));
            $consultation->setFrequenceCardique(floatval($frequenceCardiaque));
            $consultation->setRespiration(floatval($respiration));
            if ($conseilsMaladie !== null && $conseilsMaladie !== '') {
                $consultation->setConseils($conseilsMaladie);
            } else {
                $consultation->setConseils('null');}
            if ($nomMedicament !== null && $nomMedicament !== '') {
                $consultation->setMedicament($nomMedicament);
            } else {
                $consultation->setMedicament('null');}

            $consultation->setDateProchaine($dateConsultation);
            $consultation->setDureeMaladie(new \DateTime('2022-01-01'));


            // Persist et flush pour sauvegarder dans la base de données
            $entityManager->persist($consultation);
            $entityManager->flush();

            // Redirection vers une page de confirmation ou autre
            return $this->redirectToRoute('app_consultation_index');
        }



        // Si le formulaire n'a pas été soumis, rendu du formulaire
        return $this->render('consultation/new.html.twig');

       




    }

    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }
}

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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TCPDF;

use App\Repository\RendezVousRepository;


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

    #[Route('/new/{id_rdv}', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request,int $id_rdv ,RendezVousRepository $RendezVousRepository,EntityManagerInterface $entityManager,mailerinterface  $mailer): Response
    {
        $RendezVous = $RendezVousRepository->findRendezVousById($id_rdv);
        $consultation = new Consultation();
        if ($request->isMethod('POST')) {
            if ($request->isMethod('POST')) {
                $descriptionMaladie = $request->request->get('Description');
                $dureeMaladie = $request->request->get('Duré_de_maladie');
                $poids = $request->request->get('Poids_de_patient');
                $taille = $request->request->get('taille');
                $temperature = $request->request->get('temperature');
                $frequenceCardiaque = $request->request->get('frequence-cardiaque');
                $respiration = $request->request->get('respiration');
                $conseilsMaladie = $request->request->get('Conseils_de_maladie');
                $nomMedicament = $request->request->get('nomMedicament');
                $cnam = $request->request->get('cnam');
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
                $consultation->setDureeMaladie($dureeMaladie);

                $nomMedicament = $request->request->get('nomMedicament');
                $conseilsMaladie = $request->request->get('Conseils_de_maladie');


                // Persist et flush pour sauvegarder dans la base de données
                $entityManager->persist($consultation);
                $entityManager->flush();
                $this->sendEmail($nomMedicament, $conseilsMaladie, $mailer);

                if($cnam === 'on'){
                    return $this->redirectToRoute('app_cnam_new', ['id' => $consultation->getId()]);
                }
            // Redirection vers une page de confirmation ou autre
            return $this->redirectToRoute('app_consultation_index');
        }}
        // Si le formulaire n'a pas été soumis, rendu du formulaire
        return $this->render('consultation/new.html.twig', [
            'rendezVous' => $RendezVous,
        ]);

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






    #[Route('/filter', name: 'filter_consultations', methods: ['GET'])]
    public function filterConsultations(Request $request): Response
    {
        // Récupérer la durée de maladie depuis la requête
        $dureeMaladie = $request->query->get('duree_maladie');

        // Récupérer les consultations filtrées depuis le repository
        $consultations = $this->getDoctrine()->getRepository(Consultation::class)->findByDureemaladie($dureeMaladie);

        // Rendre le résultat dans un template Twig
        return $this->render('consultation/filter.html.twig', [
            'consultations' => $consultations,
        ]);
    }


    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    #[ParamConverter('consultation', class: Consultation::class)]
    public function show1(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }
    private function sendEmail($nomMedicament, $conseilsMaladie, MailerInterface $mailer): void
    {
        $emailText = "Nom du médicament : $nomMedicament\nConseils de la maladie : $conseilsMaladie";

        $email = (new Email())
            ->from(new Address('myedr@gmail.com', 'My eDr'))
            ->to('myedr83@gmail.com')
            ->subject('informations personeelles')
            ->text($emailText);

        $mailer->send($email);

    }
    /*private function sendEmail($nomMedicament, $conseilsMaladie, MailerInterface $mailer): void
    {
        $emailText = "<strong>Nom du médicament :</strong> $nomMedicament<br>";
        $emailText .= "<strong>Conseils de la maladie :</strong> $conseilsMaladie";

        $email = (new Email())
            ->from(new Address('myedr@gmail.com', 'My eDr'))
            ->to('oumayma.hasnaoui5@gmail.com')
            ->subject('Un Nouveau Question a été créé')
            ->html($emailText);

        $mailer->send($email);
    }*/

}

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
<<<<<<< HEAD
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TCPDF;

use App\Repository\RendezVousRepository;
=======
use Symfony\Component\Routing\Annotation\Route;
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b


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
<<<<<<< HEAD
    public function new(Request $request, EntityManagerInterface $entityManager,RendezVousRepository $RendezVousRepository,mailerinterface  $mailer): Response
    {
        $RendezVous = $RendezVousRepository->findRendezVousById(7);


=======
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
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b
        // Créer une nouvelle instance de Consultation
        $consultation = new Consultation();
// Récupération des données du formulaire
        if ($request->isMethod('POST')) {
            $descriptionMaladie = $request->request->get('Description');
<<<<<<< HEAD
             $dureeMaladie = $request->request->get('Duré_de_maladie');
=======
            // $dureeMaladie = $request->request->get('Duré_de_maladie');
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b
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
<<<<<<< HEAD
            $consultation->setDureeMaladie($dureeMaladie);

            $nomMedicament = $request->request->get('nomMedicament');
            $conseilsMaladie = $request->request->get('Conseils_de_maladie');
=======
            $consultation->setDureeMaladie(new \DateTime('2022-01-01'));
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b


            // Persist et flush pour sauvegarder dans la base de données
            $entityManager->persist($consultation);
            $entityManager->flush();
<<<<<<< HEAD
            $this->sendEmail($nomMedicament, $conseilsMaladie, $mailer);

=======
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b
if($cnam === 'on'){
    return $this->redirectToRoute('app_cnam_new', ['id' => $consultation->getId()]);
}

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

<<<<<<< HEAD
    /*public function show(Consultation $consultation): Response
    {
        $qrCode = new QrCode($consultation->getId());
        $qrCode->setSize(300); // Définir la taille du QR code
        $qrCode->setMargin(10); // Définir la marge du QR code
        $qrCode->setEncoding('UTF-8'); // Définir l'encodage du QR code
        $qrCode->setErrorCorrectionLevel('high'); // Définir le niveau de correction d'erreur du QR code

        // Générer l'URL du QR code en tant que données en base64
        $qrCodeUrl = 'data:images/png;base64,' . base64_encode($qrCode->writeString());

        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }*/
=======
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b
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
<<<<<<< HEAD






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
            ->subject('Un Nouveau Question a été créé')
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

=======
>>>>>>> cd29f56e5a6689dcd195b77a9fb1c8d03bff8e4b
}

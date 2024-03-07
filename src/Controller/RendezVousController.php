<?php

namespace App\Controller;

use App\Entity\RendezVous;

use App\Repository\RendezVousRepository;
//use App\Repository\UserRepository1;
use App\Command\SendAppointmentRemindersCommand;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('')]
class RendezVousController extends AbstractController
{
    #[Route('/rendez/vous', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                return $this->render('rendez_vous/index.html.twig', [
                    'rendez_vouses' => $rendezVousRepository->findAll(),
                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }


    /*
                   $userRepository = $this->getDoctrine()->getRepository(User::class);
                   $user = $userRepository->findUserById(1);

                   if (!$user) {
                       throw $this->createNotFoundException('User not found.');
                   }

                   */
    #[Route('/rendez/vous/{id}/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new( $id,Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, SendAppointmentRemindersCommand $SendAppointmentRemindersCommand): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
                $patient = $this->getUser();
                $medecin = $userRepository->find($id);


                $rendezvous = new RendezVous();
                $rendezvous->setStatusRdv('En attent');
                $rendezvous->setReponseRefuse('');
                $rendezvous->setPatient($patient);
                $rendezvous->setMedecin($medecin);
                $rendezvous->setExpert(null);
                if ($request->isMethod('POST')) {
                    $date = $request->request->get('date');
                    $time = $request->request->get('time');
                    $message = $request->request->get('description');
                    $urgence = $request->request->get('urgence');
                    $besoinAmbulance = $request->request->get('besoinAmbulance');
                    $rendezvous->setDate(new \DateTime($date));
                    $rendezvous->setTime(new \DateTime($time));
                    $rendezvous->setDescription($message);
                    $rendezvous->setReminderEmail(false);
                    $rendezvous->setUrgence($urgence === 'on');
                    $entityManager->persist($rendezvous);
                    $entityManager->flush();
                    $SendAppointmentRemindersCommand->sendReminderEmail("", $rendezvous);
                    if ($besoinAmbulance === 'on') {
                        return $this->redirectToRoute('app_ambulance_new', ['id' => $rendezvous->getId()]);
                    }
                    return $this->redirectToRoute('app_rendez_vous_patient_list', ['id_patient' => $patient->getUserIdentifier()]);
                }
                return $this->render('rendez_vous/new.html.twig', [

                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/rendez/vous/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN" ||$this->getUser()->getRoles()[0] == "ROLE_PATIENT" ) {

                return $this->render('rendez_vous/show.html.twig', [
                    'rendez_vou' => $rendezVou,
                ]);
            }else{
                return new Response( "you not the real medecin");
            }

        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/rendez/vous/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {if ($this->getUser()) {
        if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
            $idmed = $rendezVou->getMedecin()->getId();
            if ($this->getUser()->getUserIdentifier() == $idmed) {
                if ($request->isMethod('POST')) {
                    $date = $request->request->get('date');
                    $time = $request->request->get('time');
                    $message = $request->request->get('description');
                    $urgence = $request->request->get('urgence');

                    $rendezVou->setDate(new \DateTime($date));
                    $rendezVou->setTime(new \DateTime($time));
                    $rendezVou->setDescription($message);
                    $rendezVou->setUrgence($urgence === 'on');


                    $entityManager->flush();

                    return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
                }

                return $this->renderForm('rendez_vous/edit.html.twig', [
                    'rendez_vou' => $rendezVou

                ]);
            }else{
                return new Response( "you not the real medecin");
            }
        }}
        return $this->redirectToRoute('app_login');
    }

    #[Route('/rendez/vous/delete/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $idmed = $rendezVou->getMedecin()->getId();
                if ($this->getUser()->getUserIdentifier() == $idmed) {
                    if ($this->isCsrfTokenValid('delete' . $rendezVou->getId(), $request->request->get('_token'))) {
                        $entityManager->remove($rendezVou);
                        $entityManager->flush();
                    }

                    return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
                }
            }else{
                return new Response( "you not the real medecin");
            }
        }
        return $this->redirectToRoute('app_login');
    }

    ////////////////////////////////////
    #[Route('/rendez/vous/patient/{id_patient}', name: 'app_rendez_vous_patient_list')]
    public function yourAction(int $id_patient,RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {

                $appointments = $rendezVousRepository->findBy(['patient' => $id_patient]);

                $acceptedAppointments = array_filter($appointments, function (RendezVous $appointment) {
                    return $appointment->getStatusRdv() === 'Approuve_Expert_Medecin' ;
                });

                $appointments2 = $rendezVousRepository->findBy(['patient' => $id_patient]);
                $refusedAppointments = array_filter($appointments2, function (RendezVous $appointment) {
                    return str_starts_with($appointment->getStatusRdv(), 'Refuse');
                });
                $appointments3 = $rendezVousRepository->findBy(['patient' => $id_patient]);
                $pendingAppointments = array_filter($appointments3, function (RendezVous $appointment) {
                    return str_starts_with($appointment->getStatusRdv(), 'En_attente');
                });

                return $this->render('rendez_vous/indexPatient.html.twig', [
                    'acceptedAppointments' => $acceptedAppointments,
                    'refusedAppointments' => $refusedAppointments,
                    'pendingAppointments' => $pendingAppointments,
                ]);

            }

        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/rendez/vous/medecin/{medecinId}', name: 'medecin_appointments')]
    public function getMedecinAppointments(int $medecinId,RendezVousRepository $rendezVousRepository): Response
    {if ($this->getUser()) {
        if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
            $appointments = $rendezVousRepository->getAppointmentsForMedecin($medecinId);

            $currentDate = new \DateTime();

            $futureAppointments = array_filter($appointments, function (RendezVous $appointment) use ($currentDate) {
                return $appointment->getDate() > $currentDate && $appointment->getStatusRdv() === 'Approuve_Expert_Medecin';
            });

            $pastAppointments = array_filter($appointments, function (RendezVous $appointment) use ($currentDate) {
                return $appointment->getDate() <= $currentDate && $appointment->getStatusRdv() === 'Approuve_Expert_Medecin';
            });

            $pendingAppointments = array_filter($appointments, function (RendezVous $appointment) {
                return $appointment->getStatusRdv() === 'Approuve_Expert';
            });


            return $this->render('rendez_vous/indexMedecin.html.twig', [
                'futureAppointments' => $futureAppointments,
                'pastAppointments' => $pastAppointments,
                'pendingAppointments' => $pendingAppointments,// pour les traiter
            ]);
        }
    }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/rendez/vous/expert/{expertId}', name: 'expert_list')]
    public function getExpertAppointments(int $expertId,RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_EXPERT") {
                $appointments = $rendezVousRepository->getAppointmentsForExpert($expertId);
                $ALLappointments=$rendezVousRepository->findAll();
                $pendingAppointments = array_filter($ALLappointments, function (RendezVous $appointment) {
                    $status = $appointment->getStatusRdv();
                    return $status === 'En_attente';
                });


                $ALLappointments2=$rendezVousRepository->getAppointmentsForExpert($expertId);
                $processedAppointments = array_filter($ALLappointments2, function (RendezVous $appointment) {
                    $status = $appointment->getStatusRdv();
                    return $status === 'Approuve_Expert' || $status === 'Refuse_Expert';
                });

                return $this->render('rendez_vous/indexExpert.html.twig', [
                    'pendingAppointments' => $pendingAppointments,
                    'processedAppointments' => $processedAppointments,
                ]);
            }}
        return $this->redirectToRoute('app_login');
    }

    // Expert approves the appointment
    #[Route('/rendez/vous/expert_approve/{rendezvousId}', name: 'approveByExpert')]
    public function approveByExpert(int $rendezvousId, RendezVousRepository $rendezVousRepository, SendAppointmentRemindersCommand $SendAppointmentRemindersCommand, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_EXPERT") {
                $rendezvous = $rendezVousRepository->find($rendezvousId);
                $Expert = $this->getUser();
                if ($rendezvousId) {
                    $expert = $userRepository->find($Expert->getId());
                    $rendezvous->setStatusRdv('Approuve_Expert');
                    $rendezvous->setExpert($expert);
                    $entityManager->flush();
                }
                $SendAppointmentRemindersCommand->sendReminderEmail($rendezvous->getMedecin()->getEmail(), "Myedr : nouveau rendez-vous", "Vous avez un nouveau rendez-vous à traiter");
                return $this->redirectToRoute('expert_list', ['expertId' => $rendezvous->getExpert()->getId()]);

            }
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/rendez/vous/expert_refuse/{rendezvousId}', name: 'refuseByExpert')]
    public function refuseByExpert(SendAppointmentRemindersCommand $SendAppointmentRemindersCommand,int $rendezvousId, RendezVousRepository $rendezVousRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_EXPERT") {
                $rendezvous = $rendezVousRepository->find($rendezvousId);
                $Expert = $this->getUser();
                if ($rendezvousId) {
                    $expert = $userRepository->find($Expert->getId());
                    $rendezvous->setStatusRdv('Refuse_Expert');
                    $rendezvous->setExpert($expert);
                    $entityManager->flush();
                    $SendAppointmentRemindersCommand->sendReminderEmail($rendezvous->getPatient()->getEmail(), "Myedr : rendez-vous refusé", "Votre rendez-vous a été refusé d'après l'expert malheureusement");
                }
                return $this->redirectToRoute('expert_list', ['expertId' => $rendezvous->getExpert()->getId()]);
            }}
        return $this->redirectToRoute('app_login');
    }
    #[Route('/rendez/vous/medecin_approve/{rendezvousId}', name: 'approveBymedecin')]
    public function approveByDoctor(SendAppointmentRemindersCommand $SendAppointmentRemindersCommand,int $rendezvousId, RendezVousRepository $rendezVousRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $rendezvous = $rendezVousRepository->find($rendezvousId);

                if ($rendezvousId) {
                    $rendezvous->setStatusRdv('Approuve_Expert_Medecin');
                    $entityManager->flush();
                    $SendAppointmentRemindersCommand->sendReminderEmail($rendezvous->getPatient()->getEmail(), "Myedr : rendez-vous accepte", "Votre rendez-vous est accepter d'après le medecin");

                }

                return $this->redirectToRoute('medecin_appointments', ['medecinId' => $rendezvous->getMedecin()->getId()]);
            }}
        return $this->redirectToRoute('app_login');
    }

    #[Route('/rendez/vous/medecin_refuse/{rendezvousId}', name: 'refuseBymedecin')]
    public function refuseByDoctor(SendAppointmentRemindersCommand $SendAppointmentRemindersCommand,int $rendezvousId, RendezVousRepository $rendezVousRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $rendezvous = $rendezVousRepository->find($rendezvousId);

                if ($rendezvousId) {
                    $rendezvous->setStatusRdv('Refuse_Expert_Medecin');
                    $entityManager->flush();
                    $SendAppointmentRemindersCommand->sendReminderEmail($rendezvous->getPatient()->getEmail(), "Myedr : rendez-vous refusé", "Votre rendez-vous a été refusé d'après le medecin malheureusement");

                }
                return $this->redirectToRoute('medecin_appointments', ['medecinId' => $rendezvous->getMedecin()->getId()]);
            }}
        return $this->redirectToRoute('app_login');
    }
}

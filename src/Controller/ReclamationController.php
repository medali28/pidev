<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\AvisRepository;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('{id}/reclamation', name: 'app_reclamation')]
    public function index(Request $request, ManagerRegistry $managerRegistry, ReclamationRepository $repository, int $id): Response
    {
        if ($this->getUser()) {
            $patient = $this->getDoctrine()->getRepository(User::class)->find($id);
            $reclamation = new Reclamation();
            $form = $this->createForm(ReclamationType::class, $reclamation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $reclamation->setEtat('sous examination');
                $em = $managerRegistry->getManager();
                $reclamation->setDateRec(new \DateTimeImmutable());
                $reclamation->setPatient($patient);
                $reclamation->setMedecin($patient);
                $reclamation->setType("Reclamation");
                $em->persist($reclamation);
                $em->flush();

                return $this->redirectToRoute('mes_reclamations', ['id' => $id]);
            }

            return $this->render('mes_reclamation/ajouterec.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('app_login');
    }





    #[Route('{id}/mes_reclamations/{reclamationID}/detail', name: 'detail')]
    public function detail(int $id, int $reclamationID, ReclamationRepository $repository): Response
    {
        if ($this->getUser()) {
            $reclamation = $repository->find($reclamationID);
            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $reclamationID);
            }

            return $this->render('mes_reclamation/detail.html.twig', [
                'reclamation' => $reclamation
            ]);
        }
        return $this->redirectToRoute('app_login');
    }




    #[Route('{id}/mes-reclamations', name: 'mes_reclamations')]
    public function mesReclamations(ReclamationRepository $repository, int $id): Response
    {
        if ($this->getUser()) {
            $reclamations = $repository->findBy(['patient' => $id]);
            return $this->render('mes_reclamation/index.html.twig', [
                'reclamations' => $reclamations,
                'id' => $id,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/{id}/delete_reclamation/{reclamationId}', name: 'delete_reclamation')]
    public function deleteReclamation(int $id, int $reclamationId, ReclamationRepository $repository, ManagerRegistry $managerRegistry): RedirectResponse
    {
        if ($this->getUser()) {
            $reclamation = $repository->find($reclamationId);

            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $reclamationId);
            }

            $em = $managerRegistry->getManager();
            $em->remove($reclamation);
            $em->flush();

            $this->addFlash('success', 'Réclamation supprimée avec succès.');

            return $this->redirectToRoute('mes_reclamations', ['id' => $id]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/edit-reclamation', name: 'edit_reclamation')]
    public function editReclamation(Request $request, int $id, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->getUser()) {
            $reclamation = $reclamationRepository->find($id);

            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $id);
            }

            $form = $this->createForm(ReclamationType::class, $reclamation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('mes_reclamations', ['id' => $reclamation->getPatient()->getId()]);
            }

            return $this->render('mes_reclamation/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/{id}/mes-reclamations/search', name: 'search_reclamation')]
    public function searchReclamation(Request $request, ReclamationRepository $reclamationRepository, int $id): Response
    {
        if ($this->getUser()) {
            $term = $request->query->get('term');

            if (!$term) {
                return $this->redirectToRoute('mes_reclamations', ['id' => $id]);
            }

            $reclamations = $reclamationRepository->findByPatientAndSearchTerm($id, $term);

            return $this->render('mes_reclamation/index.html.twig', [
                'reclamations' => $reclamations,
                'id' => $id
            ]);
        }
        return $this->redirectToRoute('app_login');
    }


    // Adminnnnnnnnnn
    #[Route('/dashboard/reclamation/examiner/{id}/reponse', name: 'reponse')]
    public function repondreReclamation(Request $request, int $id , MailerInterface $mailer): Response
    {
        if ($this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();


            $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $id);
            }


            if ($reclamation->getReponse() !== null) {

                return $this->redirectToRoute('all_reclamation');
            }


            if ($request->isMethod('POST')) {
                $reponse = $request->request->get('reponse');
                $reclamation->setReponse($reponse);

                $reclamation->setEtat('Reclamation Traité');
                $entityManager->flush();
                $email = (new Email())
                    ->from('test@example.com')
                    ->to('myedr83@example.com')
                    ->subject('Reponse')
                    ->text($reponse);

                $mailer->send($email);
                return $this->redirectToRoute('all_reclamation');
            }

            return $this->render('admin_reclamation/reponse.html.twig', [
                'reclamation' => $reclamation,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/dashboard/reclamation/search', name: 'admin_search_reclamation')]
    public function searchReclamationadmin(Request $request, ReclamationRepository $repository): Response
    {
        if ($this->getUser()) {
            $term = $request->query->get('term');

            if (!$term) {
                return $this->redirectToRoute('all_reclamation');
            }

            $recs = $repository->findBy(['sujet' => $term]);

            return $this->render('admin_reclamation/tableuser.html.twig', [
                'recs' => $recs
            ]);
        }
        return $this->redirectToRoute('app_login');
    }



    #[Route('/dashboard/reclamation/{reclamationId}', name: 'admin/delete_reclamation')]
    public function deleteReclamationadmin(string $reclamationId, ReclamationRepository $repository, ManagerRegistry $managerRegistry): RedirectResponse
    {
        if ($this->getUser()) {
            $reclamation = $repository->find($reclamationId);

            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $reclamationId);
            }

            $em = $managerRegistry->getManager();
            $em->remove($reclamation);
            $em->flush();

            $this->addFlash('success', 'Réclamation supprimée avec succès.');

            return $this->redirectToRoute('all_reclamation');
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/dashboard/reclamation/examiner/{id}', name: 'examine_rec')]
    public function examinerReclamation(int $id, ReclamationRepository $repository): Response
    {
        if ($this->getUser()) {
            $rec = $repository->find($id);
            if (!$rec) {
                throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $id);
            }
            return $this->render('admin_reclamation/examine.html.twig', [
                'rec' => $rec
            ]);
        }
        return $this->redirectToRoute('app_login');
    }


}

<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class ReclamationController extends AbstractController
{
    #[Route('{id}/reclamation', name: 'app_reclamation')]
    public function index(Request $request, ManagerRegistry $managerRegistry, ReclamationRepository $repository, int $id): Response
    {
        $patient = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient non trouvé avec l\'ID : ' . $id);
        }

        $reclamation = new Reclamation();
        $reclamation->setPatient($patient); // Assurez-vous que le patient est associé à la réclamation

        $form = $this->createForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vous n'avez pas besoin de récupérer l'ID du patient ici car il est déjà défini dans l'objet de réclamation
            // $user = $reclamation->getPatient()->getId();
            // $reclamation->setPatient($user);
            $reclamation->setAvis('aaaa');
            $em = $managerRegistry->getManager();
            $em->persist($reclamation);
            $em->flush();



            return $this->redirectToRoute('mes_reclamations', ['id' =>$id]); // Redirection vers la page de réclamation avec l'ID du patient
        }

        return $this->render('reclamation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}




<?php

namespace App\Controller;
use App\Form\ReclamationType;


use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MesReclamationController extends AbstractController
{
    #[Route('{id}/mes-reclamations', name: 'mes_reclamations')]
    public function MesReclamations(ReclamationRepository $repository, int $id): Response
    {
        $reclamations = $repository->findBy(['patient' => $id]);

        return $this->render('mes_reclamation/index.html.twig', [
            'reclamations' => $reclamations
        ]);
    }


    #[Route('/{id}/delete_reclamation/{reclamationId}', name: 'delete_reclamation')]
    public function deleteReclamation(int $id, int $reclamationId, ReclamationRepository $repository, ManagerRegistry $managerRegistry): RedirectResponse
    {
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

    #[Route('/{id}/edit-reclamation', name: 'edit_reclamation')]
    public function editReclamation(Request $request, int $id, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = $reclamationRepository->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation non trouvée avec l\'ID : ' . $id);
        }

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Rediriger vers la page des réclamations avec l'ID du patient
            return $this->redirectToRoute('mes_reclamations', ['id' => $reclamation->getPatient()->getId()]);
        }

        return $this->render('mes_reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Cnam;
use App\Form\CnamType;
use App\Repository\CnamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cnam')]
class CnamController extends AbstractController
{



    #[Route('/', name: 'app_cnam_index', methods: ['GET'])]
    public function index(CnamRepository $cnamRepository): Response
    {
        return $this->render('cnam/index.html.twig', [
            'cnams' => $cnamRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'app_cnam_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cnam = new Cnam();
        $form = $this->createForm(CnamType::class, $cnam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cnam);
            $entityManager->flush();

            return $this->redirectToRoute('app_cnam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cnam/new.html.twig', [
            'cnam' => $cnam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cnam_show', methods: ['GET'])]
    public function show(Cnam $cnam): Response
    {
        return $this->render('cnam/show.html.twig', [
            'cnam' => $cnam,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cnam_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cnam $cnam, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CnamType::class, $cnam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cnam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cnam/edit.html.twig', [
            'cnam' => $cnam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cnam_delete', methods: ['POST'])]
    public function delete(Request $request, Cnam $cnam, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cnam->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cnam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cnam_index', [], Response::HTTP_SEE_OTHER);
    }
}

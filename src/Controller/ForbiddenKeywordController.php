<?php

namespace App\Controller;

use App\Entity\ForbiddenKeyword;
use App\Form\ForbiddenKeywordType;
use App\Repository\ForbiddenKeywordRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForbiddenKeywordController extends AbstractController


{
    #[Route('/', name: 'forbidden_keyword_index', methods: ['GET'])]
    public function index(ForbiddenKeywordRepository $forbiddenKeywordRepository): Response
    {
        $keywords = $forbiddenKeywordRepository->findAll();

        return $this->render('forbidden_keyword/index.html.twig', [
            'keywords' => $keywords,
        ]);
    }

    #[Route('/new', name: 'forbidden_keyword_new', methods: ['GET', 'POST'])]
    public function new(Request $request,ManagerRegistry $managerRegistry): Response
    {
        $keyword = new ForbiddenKeyword();
        $form = $this->createForm(ForbiddenKeywordType::class, $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($keyword);
            $entityManager->flush();

            return $this->redirectToRoute('forbidden_keyword_index');
        }

        return $this->render('forbidden_keyword/new.html.twig', [
            'keyword' => $keyword,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}/edit', name: 'forbidden_keyword_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,$id,ManagerRegistry $managerRegistry,ForbiddenKeywordRepository $forbiddenKeywordRepository): Response
    {      $keyword = $forbiddenKeywordRepository->find($id);

        $form = $this->createForm(ForbiddenKeywordType::class, $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$managerRegistry->getManager();
            $em->flush();

            return $this->redirectToRoute('forbidden_keyword_index');
        }

        return $this->render('forbidden_keyword/edit.html.twig', [
            'keyword' => $keyword,
            'form' => $form,
        ]);
    }

    #[Route('for/{id}', name: 'forbidden_keyword_delete')]
    public function delete(Request $request,ManagerRegistry $managerRegistry,$id,ForbiddenKeywordRepository $forbiddenKeywordRepository): Response
    {$keyword = $forbiddenKeywordRepository->find($id);
            $entityManager = $managerRegistry->getManager();
            $entityManager->remove($keyword);
            $entityManager->flush();


        return $this->redirectToRoute('forbidden_keyword_index');
    }
}

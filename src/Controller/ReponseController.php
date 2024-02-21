<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    #[Route('/reponse/add/{id}', name: 'app_reponse_add')]
    public function add(Request $request, ManagerRegistry $managerRegistry,QuestionRepository $questionRepository,$id): Response
    {
        $qestionid=$questionRepository->find($id);
        $reponse = new Reponse();
        $currentDateTime = new \DateTime();
        $form = $this->createForm(ReponseType::class, $reponse);
        $reponse->setDatetempR($currentDateTime);
        $reponse->setQuestion($qestionid);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $managerRegistry->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_question_show_id',['id'=> $qestionid->getId()]);
        }
        return $this->render('reponse/reponse_add.html.twig', ['f' => $form->createview()]);
    }

    #[Route('/reponse/edit/{id}', name: 'app_reponse_edit')]
    function edit(QuestionRepository $questionRepository,ReponseRepository $repository, $id, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $reponse = $repository->find($id);
        $question=$reponse->getQuestion()->getId();
        $currentDateTime = new \DateTime();
        $form = $this->createForm(ReponseType::class, $reponse);

        $reponse->setDatetempR($currentDateTime);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute('app_question_show_id',['id'=>$question]);
        }
        return $this->render('reponse/reponse_edit.html.twig', ['f' => $form->createView()]);
    }

    #[Route('/reponse/delete/{id}', name: 'app_reponse_delete')]
    function delete($id, ReponseRepository $repository, ManagerRegistry $managerRegistry) : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $reponse = $repository->find($id);
        $question=$reponse->getQuestion()->getId();
        $em = $managerRegistry->getManager();
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('app_question_show_id',['id'=>$question]);
    }


}

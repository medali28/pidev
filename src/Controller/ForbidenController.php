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

class ForbidenController extends AbstractController
{


    #[Route('/add/forbiden', name: 'add_forbiden')]
    public function ajoutCategorie(Request $request,ManagerRegistry $managerRegistry): Response
    {   $forbiden=new ForbiddenKeyword();
        $form=$this->createForm(ForbiddenKeywordType::class,$forbiden);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em=$managerRegistry->getManager();
            $em->persist($forbiden);
            $em->flush();
            return $this->redirectToRoute('tableUser');

        }
        return $this->render('forbiden/addfrb.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    #[Route('/update/forbiden/{id}', name: 'updateforbiden')]
    public function  updateCategorie(ForbiddenKeywordRepository $forbiddenKeywordRepository,ManagerRegistry $managerRegistry,$id,  Request  $request) : Response
    {
        $forbien=$forbiddenKeywordRepository->find($id);

        $form = $this->createForm(ForbiddenKeywordType::class, $forbien);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute('tableUser');
        }
        return $this->renderForm("forbiden/updatefrb.html.twig",
            ["form"=>$form]) ;


    }
    #[Route("/delete/forbiden/{id}", name:'deleteforbiden')]
    public function delete($id, ForbiddenKeywordRepository $forbiddenKeywordRepository,ManagerRegistry $managerRegistry)
    {
        $forbiden=$forbiddenKeywordRepository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($forbiden);
        $em->flush() ;
        return $this->redirectToRoute('tableUser');
    }

}

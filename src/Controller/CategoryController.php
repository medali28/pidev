<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AjoutCatType;
use App\Form\ModifcatType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{


    #[Route('/gestioncategorie', name: 'app_gestioncategorie')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'GestioncategorieController',
            'categories' => $categories,
        ]);
    }


    #[Route('/ad', name: 'add_category')]
    public function ajoutCategorie(Request $request,ManagerRegistry $managerRegistry): Response
    {   $categorie=new Category();
        $form=$this->createForm(AjoutCatType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em=$managerRegistry->getManager();
            $em->persist($categorie);
            $em->flush();
        }
        return $this->render('category/Ajoutcatg.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    #[Route('/update/categorie/{id}', name: 'updateCategorie')]
    public function  updateCategorie(CategoryRepository $categoryRepository,ManagerRegistry $managerRegistry,$id,  Request  $request) : Response
    {
        $categorie=$categoryRepository->find($id);

        $form = $this->createForm(ModifcatType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gestioncategorie');
        }
        return $this->renderForm("category/updateCat.html.twig",
            ["form"=>$form]) ;


    }

    #[Route("/delete/categorie/{id}", name:'deleteCategorie')]
    public function delete($id, CategoryRepository $categoryRepository,ManagerRegistry $managerRegistry)
    {
        $categorie=$categoryRepository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($categorie);
        $em->flush() ;
        return $this->redirectToRoute('app_gestioncategorie');
    }
}

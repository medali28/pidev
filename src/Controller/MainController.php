<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ForbiddenKeywordRepository;
use App\Repository\MedicamentRepository;
use App\Repository\ProgressBarRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
//    #[Route('/tabeuser', name: 'tableUser2222')]
//    public function index( Request $request,CategoryRepository $categoryRepository
//        ,PaginatorInterface $paginator , MedicamentRepository $medicamentRepository,
//                           ProgressBarRepository $progressBarRepository,ForbiddenKeywordRepository $forbiddenKeywordRepository): Response
//    {
//
//        $query = $categoryRepository->findAll();
//
//
//        $categories = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1), /*page number*/
//            4 /*limit per page*/
//        );
//
//        $query = $medicamentRepository->findAll();
//        $medicaments = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1), /*page number*/
//            4 /*limit per page*/
//        );
//
//        $query = $progressBarRepository->findAll();
//        $progrres = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1), /*page number*/
//            4 /*limit per page*/
//        );
//        $forbiden = $forbiddenKeywordRepository->findAll();
//
//        return $this->render('main/tableuser.html.twig',[
//            'categories' => $categories,'medicaments' => $medicaments ,'progress' => $progrres,
//            'forbidens'=>$forbiden
//        ] );
//    }

    #[Route('/admin1', name: 'app_main_admin')]
    public function admin(): Response
    {
        return $this->render('main/index.html.twig');
    }
    #[Route('/dashboard', name: 'dashboard')]
    public function viewtables(): Response
    {
        return $this->render('main/userdashbord.html.twig');
    }





}

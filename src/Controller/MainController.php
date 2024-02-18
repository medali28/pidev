<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/tabeuser', name: 'tableUser')]
    public function index(): Response
    {
        return $this->render('main/tableuser.html.twig', );
    }

    #[Route('/admin', name: 'app_main_admin')]
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

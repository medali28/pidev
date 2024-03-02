<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
           $id =  $this->getUser()->getUserIdentifier();
            return $this->redirectToRoute('profile' , ['id'=>$id]);
        }
        return $this->render('HomePage/homepage.html.twig');
    }


}

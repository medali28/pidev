<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\User;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/rendez/vous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }




        /*
                       $userRepository = $this->getDoctrine()->getRepository(User::class);
                       $user = $userRepository->findUserById(1);

                       if (!$user) {
                           throw $this->createNotFoundException('User not found.');
                       }

                       */
    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
        {

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findUserById(1);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        $rendezvous = new RendezVous();

        $rendezvous->setStatusRdv('En attent'); // Replace with your actual value
        $rendezvous->setReponseRefuse(''); // Replace with your actual value
        $rendezvous->setPatient($user);
        $rendezvous->setMedecin($user);
        $rendezvous->setExpert($user);

        if ($request->isMethod('POST')) {

            $date = $request->request->get('date');
            $time = $request->request->get('time');
            $message = $request->request->get('description');
            $urgence = $request->request->get('urgence');
            $besoinAmbulance = $request->request->get('besoinAmbulance');
            $rendezvous->setDate(new \DateTime($date));
            $rendezvous->setTime(new \DateTime($time));
            $rendezvous->setDescription($message);
            $rendezvous->setUrgence($urgence === 'on');
                $entityManager->persist($rendezvous);
                $entityManager->flush();
            if($besoinAmbulance === 'on'){
                 return $this->redirectToRoute('app_ambulance_new' , ['id' => $rendezvous->getId()]);
                }
            return $this->redirectToRoute('app_rendez_vous_index' );
        }
            return $this->render('rendez_vous/new.html.twig');
        }

            // Always pass the $rendezvous variable to the template
          //  $rendezvousId = $rendezvous->getId();

            // Redirect to the ambulance form with rendezvous ID as a parameter
           // return $this->redirectToRoute('app_rendez_vous_new', [
           //     'rendezVous' => $rendezvous,
           // ]);
      //  }

            // Persist et flush pour sauvegarder dans la base de données
           /* $entityManager->persist($rendezvous);
            $entityManager->flush();

               /* if ($rendezvous->getUrgence()) {
                    // Call the new method from AmbulanceController
                    $response = $ambulanceController->new($request, $entityManager);

                    // Redirect to the ambulance index after creating an ambulance
                    if ($response instanceof RedirectResponse) {
                        return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
                    }
                }*/
            // Redirection vers une page de confirmation ou autre
            /*return $this->render('index.html.twig', [
                'rendezVous' => $rendezvous,
            ]);
            /*return $this->redirectToRoute('app_rendez_vous_index', [
        'rendezVous' => $rendezvous,
    ]);
*/




    // Si le formulaire n'a pas été soumis, rendu du formulaire
//return $this->render('rendez_vous/new.html.twig');
//}


    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);}

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rendezVou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Cnam;
use App\Entity\Consultation;
use App\Entity\Question;
use App\Entity\RendezVous;
use App\Entity\Reponse;
use App\Form\QuestionbackType;
use App\Form\ReponseType;
use App\Repository\AmbulanceRepository;
use App\Repository\CategoryRepository;
use App\Repository\CnamRepository;
use App\Repository\ConsultationRepository;
use App\Repository\MedicamentRepository;
use App\Repository\ProgressBarRepository;
use App\Repository\QuestionRepository;
use App\Repository\RendezVousRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(rendezvousRepository  $RendezVousRepository,ambulanceRepository $ambulanceRepository): Response
    {$rendezVousData =$RendezVousRepository->findAll();
        $ambulanceData=$ambulanceRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'rendez_vouses' => $rendezVousData,
            'ambulances' => $ambulanceData,
        ]);
    }
    #[Route('/admin2', name: 'app_admin1')]
    public function afiicherissra(rendezvousRepository  $RendezVousRepository,ambulanceRepository $ambulanceRepository): Response
    {$rendezVousData =$RendezVousRepository->findAll();
        $ambulanceData=$ambulanceRepository->findAll();

        return $this->render('admin/issra.html.twig', [
            'controller_name' => 'AdminController',
            'rendez_vouses' => $rendezVousData,
            'ambulances' => $ambulanceData,
        ]);
    }

    ///sami
    #[Route('/tabeuser', name: 'tableUser')]
    public function index1( Request $request,CategoryRepository $categoryRepository,PaginatorInterface $paginator ,MedicamentRepository $medicamentRepository,ProgressBarRepository $progressBarRepository): Response
    {

        $query = $categoryRepository->findAll();


        $categories = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        $query = $medicamentRepository->findAll();
        $medicaments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        $query = $progressBarRepository->findAll();
        $progrres = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('main/tableuser.html.twig',[
            'categories' => $categories,'medicaments' => $medicaments ,'progress' => $progrres
        ] );
    }

    #[Route('/admin1', name: 'app_main_admin')]
    public function admin(): Response
    {
        return $this->render('main/index.html.twig');
    }
    //ala

    #[Route('/backend', name: 'app_backend')]
    public function index2(ReponseRepository $reponseRepository,QuestionRepository $questionRepository): Response
    {
        $question=$questionRepository->findAll();
        $reponse=$reponseRepository->findAll();
        return $this->render('back/showquestion_backend.html.twig', ['question'=>$question,'reponse'=>$reponse]);
    }
    #[Route('/backend/question_add', name: 'app_questionback_add')]
    public function add(Request $request,ManagerRegistry $managerRegistry,sluggerinterface $slugger): Response
    {
        $currentDateTime = new \DateTime();
        $question= new Question();
        $form=$this->createForm(QuestionbackType::class,$question);
        $question->setDatetempQ($currentDateTime);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        { $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image, PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $question->setImage($newFilename);
            }
            $em=$managerRegistry->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('app_backend');
        }
        return $this->render('back/question_add.html.twig',['f'=>$form->createview()]);
    }
    #[Route('/backquestion/edit/{id}', name: 'app_questionback_edit')]
    function edit(QuestionRepository $repository,$id,Request $request ,ManagerRegistry $managerRegistry,sluggerinterface $slugger)
    {
        $question= $repository->find($id);
        $currentDateTime = new \DateTime();
        $form=$this->createForm(QuestionbackType::class,$question);
        $question->setDatetempQ($currentDateTime);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            {
                $image = $form->get('image')->getData();
                if ($image) {
                    $originalFilename = pathinfo($image, PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                    try {
                        $image->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $question->setImage($newFilename);
                }
                $em = $managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute('app_backend');
            }
        }
        return $this->render('back/edit.html.twig',['f'=>$form->createView()]);
    }
    #[Route('back/questiondelete/{id}', name: 'app_questionback_delete')]
    function delete($id,QuestionRepository $repository,ManagerRegistry $managerRegistry)
    {
        $question=$repository->find($id);
        $em=$managerRegistry->getManager();
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('app_backend');
    }
    #[Route('/back/repedit/{id}', name: 'app_reponseback_edit')]
    function editr(QuestionRepository $questionRepository,ReponseRepository $repository, $id, Request $request, ManagerRegistry $managerRegistry): Response
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
            return $this->redirectToRoute('app_backend',['id'=>$question]);
        }
        return $this->render('back/reponse_edit.html.twig', ['f' => $form->createView()]);
    }

    #[Route('/reponseback/delete/{id}', name: 'app_reponseback_delete')]
    function deleter($id, ReponseRepository $repository, ManagerRegistry $managerRegistry) : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $reponse = $repository->find($id);
        $question = $reponse->getQuestion()->getId();
        $em = $managerRegistry->getManager();
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('app_backend', ['id' => $question]);
    }
    #[Route('back/reponse/add/{id}', name: 'app_reponseback_add')]
    public function addr(Request $request, ManagerRegistry $managerRegistry,QuestionRepository $questionRepository,$id): Response
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
            return $this->redirectToRoute('app_backend',['id'=> $qestionid->getId()]);
        }
        return $this->render('back/reponse_add.html.twig', ['f' => $form->createview()]);
    }

    ///Oumayma

    #[Route('/admin5', name: 'oumeymatable')]
    public function index5(ConsultationRepository $consultationRepository,CnamRepository $cnamRepository): Response
    {

        $consultations = $consultationRepository->findAll();

        $cnams = $cnamRepository->findAll();

        return $this->render('admin/oumayma.html.twig', [
            'controller_name' => 'AdminController',
            'consultations' => $consultations,
            'cnams' => $cnams,
        ]);
    }
}

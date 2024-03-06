<?php

namespace App\Controller;

use App\Entity\Cnam;
use App\Entity\Consultation;
use App\Entity\Question;
use App\Entity\RendezVous;
use App\Entity\Reponse;
use App\Entity\User;
use App\Form\QuestionbackType;
use App\Form\ReponseType;
use App\Form\UserType;
use App\Repository\AmbulanceRepository;
use App\Repository\CategoryRepository;
use App\Repository\CnamRepository;
use App\Repository\ConsultationRepository;
use App\Repository\ForbiddenKeywordRepository;
use App\Repository\MedicamentRepository;
use App\Repository\ProgressBarRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReclamationRepository;
use App\Repository\RendezVousRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(rendezvousRepository  $RendezVousRepository,ambulanceRepository $ambulanceRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

                $rendezVousData = $RendezVousRepository->findAll();
                $ambulanceData = $ambulanceRepository->findAll();

                return $this->render('admin/index.html.twig', [
                    'controller_name' => 'AdminController',
                    'rendez_vouses' => $rendezVousData,
                    'ambulances' => $ambulanceData,
                ]);

            }
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/admin2', name: 'app_admin1')]
    public function afiicherissra(rendezvousRepository  $RendezVousRepository,ambulanceRepository $ambulanceRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

                $rendezVousData = $RendezVousRepository->findAll();
                $ambulanceData = $ambulanceRepository->findAll();

                return $this->render('admin/issra.html.twig', [
                    'controller_name' => 'AdminController',
                    'rendez_vouses' => $rendezVousData,
                    'ambulances' => $ambulanceData,
                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }

    ///sami
    #[Route('/tabeuser', name: 'tableUser')]
    public function index1( Request $request,CategoryRepository $categoryRepository,PaginatorInterface $paginator , 
                            MedicamentRepository $medicamentRepository,ProgressBarRepository $progressBarRepository
        ,ForbiddenKeywordRepository $forbiddenKeywordRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

                $query = $categoryRepository->findAll();



                $categories = $paginator->paginate(
                    $query,
                    $request->query->getInt('page', 1),
                    4
                );

                $query1 = $medicamentRepository->findAll();
                $medicaments = $paginator->paginate(
                    $query1,
                    $request->query->getInt('page', 1),
                    4
                );

                $query2 = $progressBarRepository->findAll();
                $progrres = $paginator->paginate(
                    $query2,
                    $request->query->getInt('page', 1),
                    4
                );

                $forbiden=$forbiddenKeywordRepository->findAll();
                return $this->render('main/tableuser.html.twig', [
                    'categories' => $categories, 'medicaments' => $medicaments, 'progress' => $progrres,
                    'forbidens' => $forbiden,]);
            }
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin1', name: 'app_main_admin')]
    public function admin(): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

                return $this->render('main/index.html.twig');

            }
        }
        return $this->redirectToRoute('app_login');

    }


    #[Route('/backend', name: 'app_backend')]
    public function index2(ReponseRepository $reponseRepository,QuestionRepository $questionRepository): Response
    {
        if ($this->getUser()) {

            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                $question = $questionRepository->findAll();
                $reponse = $reponseRepository->findAll();
                return $this->render('back/showquestion_backend.html.twig', ['question' => $question, 'reponse' => $reponse]);
            }
        }
        return $this->redirectToRoute('app_login');

    }
    #[Route('/backend/question_add', name: 'app_questionback_add')]
    public function add(Request $request,ManagerRegistry $managerRegistry,sluggerinterface $slugger): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                $currentDateTime = new \DateTime();
                $question = new Question();
                $form = $this->createForm(QuestionbackType::class, $question);
                $question->setDatetempQ($currentDateTime);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
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
                    $em->persist($question);
                    $em->flush();
                    return $this->redirectToRoute('app_backend');
                }
                return $this->render('back/question_add.html.twig', ['f' => $form->createview()]);
            }
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/backquestion/edit/{id}', name: 'app_questionback_edit')]
    function edit(QuestionRepository $repository,$id,Request $request ,ManagerRegistry $managerRegistry,sluggerinterface $slugger)
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                $question = $repository->find($id);
                $currentDateTime = new \DateTime();
                $form = $this->createForm(QuestionbackType::class, $question);
                $question->setDatetempQ($currentDateTime);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
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
                return $this->render('back/edit.html.twig', ['f' => $form->createView()]);
            }
        }
        return $this->redirectToRoute('app_login');

    }
    #[Route('back/questiondelete/{id}', name: 'app_questionback_delete')]
    function delete($id,QuestionRepository $repository,ManagerRegistry $managerRegistry)
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                $question = $repository->find($id);
                $em = $managerRegistry->getManager();
                $em->remove($question);
                $em->flush();
                return $this->redirectToRoute('app_backend');
            }
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/back/repedit/{id}', name: 'app_reponseback_edit')]
    function editr(QuestionRepository $questionRepository,ReponseRepository $repository, $id, Request $request, ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
            $reponse = $repository->find($id);
            $question = $reponse->getQuestion()->getId();
            $currentDateTime = new \DateTime();
            $form = $this->createForm(ReponseType::class, $reponse);

            $reponse->setDatetempR($currentDateTime);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute('app_backend', ['id' => $question]);
            }
            return $this->render('back/reponse_edit.html.twig', ['f' => $form->createView()]);
        }}
        return $this->redirectToRoute('app_login');

    }

    #[Route('/reponseback/delete/{id}', name: 'app_reponseback_delete')]
    function deleter($id, ReponseRepository $repository, ManagerRegistry $managerRegistry) : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->getUser() ) {
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
                $reponse = $repository->find($id);
                $question = $reponse->getQuestion()->getId();
                $em = $managerRegistry->getManager();
                $em->remove($reponse);
                $em->flush();
                return $this->redirectToRoute('app_backend', ['id' => $question]);
            }
        }
        return $this->redirectToRoute('app_login');

    }
    #[Route('back/reponse/add/{id}', name: 'app_reponseback_add')]
    public function addr(Request $request, ManagerRegistry $managerRegistry,QuestionRepository $questionRepository,$id): Response
    {
        if ($this->getUser()) {
            $qestionid = $questionRepository->find($id);
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
                return $this->redirectToRoute('app_backend', ['id' => $qestionid->getId()]);
            }
            return $this->render('back/reponse_add.html.twig', ['f' => $form->createview()]);
        }
        return $this->redirectToRoute('app_login');

    }

    ///Oumayma

    #[Route('/admin5', name: 'oumeymatable')]
    public function index5(ConsultationRepository $consultationRepository,CnamRepository $cnamRepository): Response
    {
        if ($this->getUser()) {
            $consultations = $consultationRepository->findAll();

            $cnams = $cnamRepository->findAll();

            return $this->render('admin/oumayma.html.twig', [
                'controller_name' => 'AdminController',
                'consultations' => $consultations,
                'cnams' => $cnams,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin6', name: 'all_user')]
    public function allUser(UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $users = $repository->findAll();
            return $this->render('useradmin/tableuser.html.twig', [
                'users' => $users
            ]);


        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin7', name: 'Add_Expert')]
    public function addExpert(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = new User();
            $form = $this->createForm(UserType::class, $user, [
                'expert' => true,
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $emailexist = $repository->findOneBy(['email' => $user->getEmail()]);
                if ($emailexist) {
                    $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already existe.'));
                    return $this->render('useradmin/addExpert.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
                $user->setDateCreateCompte(new \DateTimeImmutable());
                $user->setRoles(['ROLE_EXPERT']);
                $user->setRate(100);
                $user->setValidation(1);
                $user->setActive(true);
                $em = $managerRegistry->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('all_user');
            }
            return $this->render('useradmin/addExpert.html.twig', ['form' => $form->createView()]);
        }
        return $this->redirectToRoute('app_login');

    }

        #[Route('/admin/desactive/{id}', name: 'deactive_user')]
    public function deleteuser($id, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $repository->find($id);
            $em = $managerRegistry->getManager();
            if ($user->getActive()) {
                $user->setActive(false);
            } else {
                $user->setActive(true);
            }

            $em->flush();
            return $this->redirectToRoute('all_user');
        }
        return $this->redirectToRoute('app_login');

    }



    #[Route('/admin8', name: 'all_reclamation')]
    public function allReclamation(ReclamationRepository $repository,): Response
    {
        $recs = $repository->findAll();
        return $this->render('admin_reclamation/tableuser.html.twig', [
            'recs' => $recs
        ]);
    }


}

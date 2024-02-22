<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserController extends AbstractController
{


    #[Route('/register', name: 'Register')]
    public function Register(Request $request ,UserPasswordHasherInterface $passwordHasher ,UserRepository $repository ,ManagerRegistry $managerRegistry ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailexist = $repository->findOneBy(['email' => $user->getEmail()]);
            if ($emailexist){
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already existe.'));
                return $this->render('user/Register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }


            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setDateCreateCompte(new \DateTimeImmutable());
            $user->setLastModifyData(new \DateTimeImmutable());
            $user->setLastModifyPassword(new \DateTimeImmutable());

            $roleselect = $request->get('roleSelect');
            if ($roleselect == 'patient'){
                $user->setRoles(['ROLE_PATIENT']);
                $user->setDureRdv(null);
                $user->setRate(100);
                $user->setValidation(1);
                $user->setMaladieChronique($request->get('maladie_chronique'));
            }else{
                $duree_rdv = \DateTime::createFromFormat('i:s', $request->get('dure_rdv'));
            if ($duree_rdv instanceof \DateTime) {
                $user->setDureRdv($duree_rdv);
            }else{
                $user->setDureRdv(null);
            }
            $user->setRate(100);
            $user->setValidation(0);
            $user->setRoles(['ROLE_MEDECIN']);
            }
            $user->setGender($request->get('gender'));
            $user->setGroupeSanguin($request->get('groupe_sanguin'));
            $dateDebut = \DateTime::createFromFormat('H:i', $request->get('date_debut'));
            if ($dateDebut instanceof \DateTime) {
                $user->setDateDebut($dateDebut);
            }else{
                $user->setDateDebut(null);
            }
            $datenais = \DateTime::createFromFormat('Y-m-d', $request->get('date_naissance'));
            if ($datenais instanceof \DateTime) {
                $user->setDateNaissance($datenais);
            }
            $dateFin = \DateTime::createFromFormat('H:i', $request->get('date_fin'));
            if ($dateFin instanceof \DateTime) {
                $user->setDateFin($dateFin);
            }else{
                $user->setDateFin(null);
            }
//
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                        return $this->render('user/Register.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    $user->setImage($newFilename);
                } else {
                    $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                    return $this->render('user/Register.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            $deplomeFile = $form->get('diplomes')->getData();
            if ($deplomeFile) {

                if ($deplomeFile->getMimeType() === 'image/jpeg' || $deplomeFile->getMimeType() === 'image/png') {
                    $Filename = uniqid().'.'.$deplomeFile->guessExtension();
                    try {
                        $deplomeFile->move(
                            $this->getParameter('brochures_directory'),
                            $Filename
                        );
                    } catch (FileException $e) {
                        $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                        return $this->render('user/Register.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    $user->setDiplomes($Filename);
                } else {
                    $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid2.'));
                    return $this->render('user/Register.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
//            return  new Response($user->getId());
            return $this->redirectToRoute('Update_data', ['id' => $user->getId()]);
        }

        return $this->render('user/Register.html.twig', [
            'form' => $form->createView(),
        ]);
//        return $this->render('user/Register.html.twig');
    }

    #[Route('/{id}/update', name: 'Update_data')]
    public function modifyData(Request $request, int $id  ,UserRepository $repository ,ManagerRegistry $managerRegistry ): Response
    {
        $user = $repository->find($id);
        $em= $managerRegistry->getManager();
        if (in_array('ROLE_MEDECIN', $user->getRoles())){
            $form = $this->createForm(UserType::class, $user , [
                'medecin'=>true
            ]);
            $role = "medecin";
        }elseif (in_array('ROLE_EXPERT', $user->getRoles())){
            $form = $this->createForm(UserType::class, $user , [
                'expert_without_pass'=>true
            ]);
            $role = "expert";
        }elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
            $form = $this->createForm(UserType::class, $user , [
                'patient'=>true
            ]);
            $role = "patient";
        }
        else {
            return new Response(" u can't modify data of admin");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted()  ) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                        $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                        return $this->render('user/Modify.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    $user->setImage($newFilename);
                } else {
                    $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                    return $this->render('user/Modify.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
         if ($role == "medecin" ){
             $deplomeFile = $form->get('diplomes')->getData();
             if ($deplomeFile) {
                 if ($deplomeFile->getMimeType() === 'image/jpeg' || $deplomeFile->getMimeType() === 'image/png') {
                     $Filename = uniqid().'.'.$deplomeFile->guessExtension();
                     try {
                         $deplomeFile->move(
                             $this->getParameter('brochures_directory'),
                             $Filename
                         );
                     } catch (FileException $e) {
                         $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                         return $this->render('admin/ModifyUser.html.twig', [
                             'form' => $form->createView(),
                         ]);
                     }
                     $user->setDiplomes($Filename);
                 } else {
                     $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid2.'));
                     return $this->render('admin/ModifyUser.html.twig', [
                         'form' => $form->createView(),
                     ]);
                 }
             }
         }
            $em->flush();
            return  $this->redirectToRoute('Update_data' , ['id'=>$user->getId()]);
        }

        return $this->render('user/Modify.html.twig', [
            'form' => $form->createView(),
            'role'=>$role,
            'user'=>$user
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request , UserRepository $repository , UserPasswordHasherInterface $passwordEncoder): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class ,[
                'constraints' => [
                    new NotBlank(message: 'email doit etre non vide') ,
                ]
            ])
            ->add('password', PasswordType::class ,[
                'constraints' => [
                    new NotBlank(message: 'password  doit etre non vide ') ,

                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $repository->findOneBy(['email' => $data['email']]);

            if (!$user) {
                $form->get('email')->addError(new FormError('Email not found'));
                return $this->render('user/login.html.twig', ['form' => $form->createView()]);
            }else{
                if ($passwordEncoder->isPasswordValid($user,  $data['password'])) {
                    return new Response('Logged in successfully!');
                } else {
                    $form->get('password')->addError(new FormError('password Incorrect'));
                    return $this->render('user/login.html.twig', ['form' => $form->createView()]);
                }
            }

        }

        return $this->render('user/login.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/{id}/delete', name: 'delete_user')]
    public function delete($id,UserRepository $repository,ManagerRegistry $managerRegistry): Response
    {
        $user = $repository ->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_home_page');
    }


    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {

        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/dashboard/alluser', name: 'all_user')]
    public function allUser(UserRepository $repository,ManagerRegistry $managerRegistry): Response
    {
        $users = $repository ->findAll();
        return $this->render('admin/tableuser.html.twig', [
            'users' =>$users
        ]);
    }

    #[Route('/dashboard/addexpert', name: 'Add_Expert')]
    public function addExpert(Request $request ,UserPasswordHasherInterface $passwordHasher ,UserRepository $repository ,ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'expert' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() &&  $form->isValid()) {
            $emailexist = $repository->findOneBy(['email' => $user->getEmail()]);
            if ($emailexist){
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already existe.'));
                return $this->render('admin/addExpert.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setDateCreateCompte(new \DateTimeImmutable());
            $user->setRoles(['ROLE_EXPERT']);
            $user->setRate(100);
            $user->setValidation(1);
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('all_user');
        }
        return $this->render('admin/addExpert.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/dashboard/update/{id}', name: 'Update_user')]
    public function updateUser(Request $request, int $id  ,UserRepository $repository ,ManagerRegistry $managerRegistry ): Response
    {
        $user = $repository->find($id);
        $em= $managerRegistry->getManager();
       if (in_array('ROLE_MEDECIN', $user->getRoles())){
           $form = $this->createForm(UserType::class, $user , [
               'medecin'=>true
           ]);
           $role = "medecin";
       }elseif (in_array('ROLE_EXPERT', $user->getRoles())){
           $form = $this->createForm(UserType::class, $user , [
               'expert_without_pass'=>true
           ]);
           $role = "expert";
       }elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
           $form = $this->createForm(UserType::class, $user , [
               'patient'=>true
           ]);
           $role = "patient";
       }
       else {
            return new Response(" u can't modify data of admin");
       }
        $form->handleRequest($request);
        if ($form->isSubmitted()  ) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                        $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                        return $this->render('admin/ModifyUser.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    $user->setImage($newFilename);
                } else {
                    $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                    return $this->render('admin/ModifyUser.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            $deplomeFile = $form->get('diplomes')->getData();
            if ($deplomeFile) {
                if ($deplomeFile->getMimeType() === 'image/jpeg' || $deplomeFile->getMimeType() === 'image/png') {
                    $Filename = uniqid().'.'.$deplomeFile->guessExtension();
                    try {
                        $deplomeFile->move(
                            $this->getParameter('brochures_directory'),
                            $Filename
                        );
                    } catch (FileException $e) {
                        $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                        return $this->render('admin/ModifyUser.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                    $user->setDiplomes($Filename);
                } else {
                    $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid2.'));
                    return $this->render('admin/ModifyUser.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            $em->flush();
            return  $this->redirectToRoute('all_user');
        }

        return $this->render('admin/ModifyUser.html.twig', [
            'form' => $form->createView(),
            'role'=>$role
        ]);
    }
}

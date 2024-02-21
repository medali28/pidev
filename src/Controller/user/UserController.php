<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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
             $user->setMaladieChronique($request->get('maladie_chronique'));
            $user->setDateNaissance(DateTime::createFromFormat('Y-m-d', $request->get('date_naissance')));
            $role = $request->get('roleSelect');
            if ($role == 'patient'){
                $user->setRoles(['ROLE_PATIENT']);
            }else{
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
            $dateFin = \DateTime::createFromFormat('H:i', $request->get('date_fin'));
            if ($dateFin instanceof \DateTime) {
                $user->setDateFin($dateFin);
            }else{
                $user->setDateFin(null);
            }
//            $user->setDureRdv($request->get('dure_rdv'));
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
        $em= $managerRegistry->getManager();
        $user = $repository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return  new Response("data update");
        }

        return $this->render('user/Modify.html.twig', [
            'form' => $form->createView(),
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
//            ->add('submit', SubmitType::class, ['label' => 'Login'])
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
}

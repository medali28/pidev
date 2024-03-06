<?php

namespace App\Controller;
use App\Entity\RendezVous;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\AvisRepository;
use App\Repository\RendezVousRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
use Symfony\Component\Validator\Constraints\Email as m;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
//use App\Security\EmailVerifier;
use Symfony\Component\Mime\Email as vEmail;

class UserController extends AbstractController
{
    #[Route('/register', name: 'Register')]
    public function Register(SessionInterface $session, Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailexist = $repository->findOneBy(['email' => $user->getEmail()]);
            if ($emailexist) {
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already existe.'));
                return $this->render('user/Register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }


            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setDateCreateCompte(new \DateTimeImmutable());
            $user->setLastModifyData(new \DateTimeImmutable());
            $user->setLastModifyPassword(new \DateTimeImmutable());
            $user->setActive(true);
            $roleselect = $request->get('roleSelect');
            if ($roleselect == 'patient') {
                $user->setRoles(['ROLE_PATIENT']);
                $user->setDureRdv(null);
                $user->setRate(100);
                $user->setActive(true);
                $user->setMaladieChronique($request->get('maladie_chronique'));
            } else {
                $duree_rdv = \DateTime::createFromFormat('i:s', $request->get('dure_rdv'));
                if ($duree_rdv instanceof \DateTime) {
                    $user->setDureRdv($duree_rdv);
                } else {
                    $user->setDureRdv(null);
                }
                $user->setRate(100);
                $user->setActive(true);
                $user->setRoles(['ROLE_MEDECIN']);
            }
            $user->setGender($request->get('gender'));
            $user->setPays($request->get('countryInput'));
            $user->setVille($request->get('stateDisplay'));
            $user->setLat($request->get('latitudeDisplay'));
            $user->setLog($request->get('longitudeDisplay'));
            $user->setSpecialite($request->get('specialite'));
            $user->setGroupeSanguin($request->get('groupe_sanguin'));
            $dateDebut = \DateTime::createFromFormat('H:i', $request->get('date_debut'));
            if ($dateDebut instanceof \DateTime) {
                $user->setDateDebut($dateDebut);
            } else {
                $user->setDateDebut(null);
            }
            $datenais = \DateTime::createFromFormat('Y-m-d', $request->get('date_naissance'));
            if ($datenais instanceof \DateTime) {
                $user->setDateNaissance($datenais);
            }
            $dateFin = \DateTime::createFromFormat('H:i', $request->get('date_fin'));
            if ($dateFin instanceof \DateTime) {
                $user->setDateFin($dateFin);
            } else {
                $user->setDateFin(null);
            }
//
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
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
                    $Filename = uniqid() . '.' . $deplomeFile->guessExtension();
                    try {
                        $deplomeFile->move(
                            $this->getParameter('images_directory'),
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

            $session->set('user', $user);
            return $this->redirectToRoute('verify_email');
        }

        return $this->render('user/Register.html.twig', [
            'form' => $form->createView(),
        ]);
//        return $this->render('user/Register.html.twig');
    }

    #[Route('/verify_email', name: 'verify_email')]
    public function verifyEmail(SessionInterface $session, MailerInterface $mailer, Request $request, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {

        $user = $session->get('user');
//        $form = $this->createForm(UserType::class, $user, [
//            'email_verify' => true
//        ]);
//        return new Response($user->getFirstName());

        $htmlContent = "<html>
    <head>
        <title>Email de confirmation</title>
    </head>
    <body>
        <p>Cher destinataire,{{ lname }} {{ fname }}</p>
        <p>Votre code à 6 chiffres est: <strong>{{ code }}</strong></p>
    </body>
    </html>";

        if ($request->isMethod('POST')) {
            $code_tep = $request->request->get('code');
            $savedCode = $session->get('code');
            if (!empty($code_tep) && !empty($savedCode)) {
                if ($code_tep == $savedCode) {
                    $em = $managerRegistry->getManager();
                    $user->setValidation(1);
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('app_login');
                }

            }
//                return new Response("heeeeeeeloo");


        } else {
            $code = mt_rand(100000, 999999);
            $session->set('code', $code);
            $fname = $user->getFirstName();
            $lname = $user->getLastName();
            $htmlContent = str_replace(['{{ code }}', '{{ fname }}', '{{ lname }}'], [$code, $fname, $lname], $htmlContent);
            $email = (new \Symfony\Component\Mime\Email())
                ->from('myedr@gmail.com')
                ->to('myedr83@gmail.com')
                ->subject('Email validation')
                ->html($htmlContent);

            try {
                $mailer->send($email);
                $send = true;
            } catch (TransportExceptionInterface $e) {
            }
        }

        return $this->render('user/ValidationCompte.html.twig', [
            'email' => $user->getEmail(),
            'code' => $code
        ]);
    }

    #[Route('/profile/{id}/update', name: 'Update_data')]
    public function modifyData(Request $request, int $id, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $repository->find($id);
            $em = $managerRegistry->getManager();
            if (in_array('ROLE_MEDECIN', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'medecin' => true
                ]);
                $role = "medecin";
            } elseif (in_array('ROLE_EXPERT', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'expert_admin' => true
                ]);
                $role = "expert";
            } elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'patient' => true
                ]);
                $role = "patient";
            } else {
                return new Response(" u can't modify data of admin");
            }
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                        $newFilename = uniqid() . '.' . $imageFile->guessExtension();
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
                if ($role == "medecin") {
                    $deplomeFile = $form->get('diplomes')->getData();
                    if ($deplomeFile) {
                        if ($deplomeFile->getMimeType() === 'image/jpeg' || $deplomeFile->getMimeType() === 'image/png') {
                            $Filename = uniqid() . '.' . $deplomeFile->guessExtension();
                            try {
                                $deplomeFile->move(
                                    $this->getParameter('brochures_directory'),
                                    $Filename
                                );
                            } catch (FileException $e) {
                                $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                                return $this->render('useradmin/ModifyUser.html.twig', [
                                    'form' => $form->createView(),
                                ]);
                            }
                            $user->setDiplomes($Filename);
                        } else {
                            $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid2.'));
                            return $this->render('useradmin/ModifyUser.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                    }
                }
                $em->flush();
                return $this->redirectToRoute('Update_data', ['id' => $user->getId()]);
            }

            return $this->render('user/Modify.html.twig', [
                'form' => $form->createView(),
                'role' => $role,
                'user' => $user
            ]);
        }
        return $this->redirectToRoute('app_login');

    }

    #[Route('/profile/{id}/changepass', name: 'change_pass')]
    public function changepassword(Request $request, int $id, UserRepository $repository, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordEncoder, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $repository->find($id);
            $em = $managerRegistry->getManager();
            $form = $this->createForm(UserType::class, $user, [
                'password' => true
            ]);
            $oldpass = $request->request->get('oldpass');
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($passwordEncoder->isPasswordValid($user, $oldpass)) {

//              $form->get('oldpass')->addError(new FormError('password not incorrect'));
                    return $this->render('user/ChangePassword.html.twig', ['form' => $form->createView()]);
                }
                $user->setPassword($passwordEncoder->hashPassword($user, $form->get('password')->getData()));
                $em->flush();
                return $this->redirectToRoute('Update_data', ['id' => $user->getId()]);
            }

            return $this->render('user/ChangePassword.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ]);
        }
        return $this->redirectToRoute('app_login');


    }




//    #[Route('/{id}/delete', name: 'delete_user')]
//    public function delete($id, UserRepository $repository, ManagerRegistry $managerRegistry ,AuthenticationUtils $authenticationUtils): Response
//    {
//
//        $user = $repository ->find($id);
//        $em = $managerRegistry->getManager();
//        $em->remove($user);
//        $em->flush();
//        return $this->redirectToRoute('app_home_page');
//    }
    #[Route('/profile/{id}', name: 'profile')]
    public function profile(AuthenticationUtils $authenticationUtils,$id ,UserRepository $userRepository , EntityManagerInterface $entityManager,RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->getUser()) {
//            if ($this->getUser()->getRoles()[0] == "ROLE_EXPERT" ){
//                $user = $userRepository->find($id);
//                if (!$user->getDisponibilite()){
//                    $user->setDisponibilite(True);
////                    $entityManager->persist($user);
//                    $entityManager->flush();
//                }
//                $rdvs = $rendezVousRepository->findBy(['expert' => null]);
//                foreach ($rdvs as $rdv) {
//                    $rdv->setExpert($this->getUser());
////                    $entityManager->persist($rdv);
//                    $entityManager->flush();
//                }
//        }
            return $this->render('main/main.html.twig');

        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/profile/{id}/medecin/{idmed}/details', name: 'details_medcin')]
    public function detailsmed($idmed , AvisRepository $avisRepository , UserRepository $userRepository ,AuthenticationUtils $authenticationUtils,$id , EntityManagerInterface $entityManager,RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->getUser()) {
            $med = $userRepository->find($idmed);
            $avis = $avisRepository->findBy(['medecin' => $med]);
            return $this->render('user/detailsmed.html.twig', [
                'medecins' => $med,
                'avis' => $avis,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {

            return $this->render('useradmin/dashboard.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/dashboard/update/{id}', name: 'Update_user')]
    public function updateUser(Request $request, int $id, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $repository->find($id);
            $em = $managerRegistry->getManager();
            if (in_array('ROLE_MEDECIN', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'medecin_admin' => true
                ]);
                $role = "medecin";
            } elseif (in_array('ROLE_EXPERT', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'expert_admin' => true
                ]);
                $role = "expert";
            } elseif (in_array('ROLE_PATIENT', $user->getRoles())) {
                $form = $this->createForm(UserType::class, $user, [
                    'patient_admin' => true
                ]);
                $role = "patient";
            } else {
                return new Response(" u can't modify data of admin");
            }
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($role != "expert") {
                    $imageFile = $form->get('image')->getData();
                    if ($imageFile) {
                        if ($imageFile->getMimeType() === 'image/jpeg' || $imageFile->getMimeType() === 'image/png') {
                            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                            try {
                                $imageFile->move(
                                    $this->getParameter('brochures_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {

                                $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                                return $this->render('useradmin/ModifyUser.html.twig', [
                                    'form' => $form->createView(),
                                ]);
                            }
                            $user->setImage($newFilename);
                        } else {
                            $form->get('image')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                            return $this->render('useradmin/ModifyUser.html.twig', [
                                'form' => $form->createView(),
                            ]);
                        }
                    }
                    if ($role == "medecin") {
                        $deplomeFile = $form->get('diplomes')->getData();
                        if ($deplomeFile) {
                            if ($deplomeFile->getMimeType() === 'image/jpeg' || $deplomeFile->getMimeType() === 'image/png') {
                                $Filename = uniqid() . '.' . $deplomeFile->guessExtension();
                                try {
                                    $deplomeFile->move(
                                        $this->getParameter('brochures_directory'),
                                        $Filename
                                    );
                                } catch (FileException $e) {
                                    $form->get('diplomes')->addError(new \Symfony\Component\Form\FormError('This image is invalid.'));
                                    return $this->render('useradmin/ModifyUser.html.twig', [
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
                }
                $em->flush();
                return $this->redirectToRoute('all_user');
            }

            return $this->render('useradmin/ModifyUser.html.twig', [
                'form' => $form->createView(),
                'role' => $role
            ]);

        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/profile/{id}/medecin', name: 'medecin')]
    public function getMedecin($id, Request $request, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            $form = $this->createFormBuilder()
                ->add('prix', IntegerType::class, [
                    'required' => false,
                ])
                ->getForm();

            $form->handleRequest($request);
            $specialite = "None";
            $prix = null;
            $pays = "none";
            $ville = "none";
            $meds = $repository->findMedecinsByCriteria($specialite, $prix, $pays, $ville);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                $specialite = $request->request->get('specialite');
                $prix = $data['prix'];
                $pays = $request->request->get('countryh');
                $ville = $request->request->get('stateh');
                $meds = $repository->findMedecinsByCriteria($specialite, $prix, $pays, $ville);
            }
            return $this->render('user/medecin.html.twig', [
                'meds' => $meds,
                'form' => $form->createView(),
                'id' => $id
            ]);
        }
        return $this->redirectToRoute('app_login');

    }


    #[Route('/profile/{id}/medecin/map/{lat}/{lng}', name: 'map')]
    public function map($id, $lat, $lng, Request $request, UserRepository $repository, ManagerRegistry $managerRegistry, AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('user/map.html.twig', [
            'latd' => $lat,
            'lngd' => $lng
        ]);
    }

}

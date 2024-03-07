<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email as m;
use Symfony\Component\Validator\Constraints\NotBlank;

//class SecurityController extends AbstractController
//{
//    #[Route('/security', name: 'app_security')]
//    public function index(): Response
//    {
//        return $this->render('security/index.html.twig', [
//            'controller_name' => 'SecurityController',
//        ]);
//    }
//
//    #[Route(path: '/login', name: 'app_login')]
//    public function login(AuthenticationUtils $authenticationUtils): Response
//    {
//        // if ($this->getUser()) {
//        //     return $this->redirectToRoute('target_path');
//        // }
//
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
//    }
//
//    #[Route(path: '/logout', name: 'app_logout')]
//    public function logout(): void
//    {
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
//    }
//}

class SecurityController extends AbstractController
{
//    private $code;
//
//    public function __construct(int $code)
//    {
//        $this->code = $code;
//    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, UserRepository $userRepository, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile', ['id' => $this->getUser()->getUserIdentifier()]);
        }
        $email = $authenticationUtils->getLastUsername();
        if ($email != "") {
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $exception = new UserNotFoundException();
                $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('user/login.html.twig', ['last_username' => $email, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_test');
    }


//    public function genrateCode()
//    {
//        return 15236;
//    }

    /**
     * @throws TransportExceptionInterface
     */

    #[Route(path: '/forgot', name: 'forgot')]
    public function forgotPassword(  SessionInterface $session,MailerInterface $mailer, Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, ManagerRegistry $managerRegistry,): \Symfony\Component\HttpFoundation\RedirectResponse|Response
    {

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(message: "not empty"),
                    new m(message: "email invalid format")
                ]
            ])
            ->getForm();


        $form->handleRequest($request);
        $code_tep = $request->request->get('code');
//        $code_tep = $form->getData();
        $savedCode = $session->get('code');
        $id =  $session->get('id');
        if (!empty($code_tep) && !empty($savedCode)){
            if ($code_tep == $savedCode){
                return $this->redirectToRoute("reset_password" , ['id'=>$id]);
            }

        }
        if ($form->isSubmitted() && $form->isValid() ) {
            $donnees = $form->getData();
            $user = $userRepository->findOneBy(['email' => $donnees]);
            if (!$user) {
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('Cet email n\'existe pas.'));
                return $this->render("user/forgotPassword.html.twig", [
                    'form' => $form->createView(),
                    'send' => false,
                    'code' => 0
                ]);

            }

            $session->set('id', $user->getId());
            $htmlContent = "<html>
    <head>
        <title>Email de confirmation</title>
    </head>
    <body>
        <p>Cher destinataire,{{ lname }} {{ fname }}</p>
        <p>Votre code à 6 chiffres est: <strong>{{ code }}</strong></p>
    </body>
    </html>";
            $code = mt_rand(100000, 999999);
            $session->set('code', $code);
            $fname = $user->getFirstName();
            $lname = $user->getLastName();
            $htmlContent = str_replace(['{{ code }}', '{{ fname }}','{{ lname }}'], [$code, $fname,$lname], $htmlContent);
            $email = (new Email())
                ->from('myedr@gmail.com')
                ->to('myedr83@gmail.com')
                ->subject('Email validation')
                ->html($htmlContent);

            try {
                $mailer->send($email);
                $send = true;

            } catch (TransportExceptionInterface $e) {
                $send = false;
            }
            return $this->render("user/forgotPassword.html.twig", [
                'form' => $form->createView(),
                'send' => $send,
                'code' => $code
            ]);


        }
        $session->remove('code');
        $session->remove('id');
        return $this->render("user/forgotPassword.html.twig", [
            'form' => $form->createView(),
            'send'=>false,
            'code' =>0
        ]);
    }


    #[Route(path: '/reset_pass/{id}', name: 'reset_password')]
    public function resetPassword(  SessionInterface $session,Request $request, UserPasswordHasherInterface $passwordEncoder , int $id, UserRepository $repository, ManagerRegistry $managerRegistry): Response
    {
        $session->remove('code');
        $session->remove('id');
        $user = $repository->find($id);
        $em = $managerRegistry->getManager();
        $form = $this->createForm(UserType::class, $user, [
            'password' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->hashPassword($user, $form->get('password')->getData()));

            $em->flush();
//            return new Response("test");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/ResetPassword.html.twig' , [
            'form' => $form->createView(),
        ]) ;
    }



}

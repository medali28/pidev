<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
//use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email as m;
use Symfony\Component\Mime\Email;
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
//         $email = $request->request->get('email');
//        return new Response('email' . $email );
        $email = $authenticationUtils->getLastUsername();
        if ($email != "") {
            if (!$userRepository->findOneBy(['email' => $email])) {
                $exception = new UserNotFoundException();
                $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            }
        }
//        $password = $request->request->get('password');
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('user/login.html.twig', ['last_username' => $email, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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

//            $htmlContent = str_replace('{{ fname }}', $fname, $htmlContent);
//            $htmlContent = str_replace('{{ lname }}', $lname, $htmlContent);
                    $email = (new Email())
                        ->from('myedr@gmail.com')
                        ->to('test@gmail.com')
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


    #[Route(path: '/reset/{id}', name: 'reset_password')]
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
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/ResetPassword.html.twig' , [
            'form' => $form->createView(),
        ]) ;
}
//    #[Route(path: '/forgot/resetpassword', name: 'app_reset_password')]
//    public function resetpassword(Request $request, UserPasswordHasherInterface $passwordHasher ,int $id, UserRepository $repository, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordEncoder, AuthenticationUtils $authenticationUtils): Response
//    {
//            $user = $repository->find($id);
//            $em = $managerRegistry->getManager();
//            $form = $this->createForm(UserType::class, $user, [
//                'password' => true
//            ]);
//            $oldpass = $request->request->get('oldpass');
//            $form->handleRequest($request);
//            if ($form->isSubmitted()) {
//                if ($passwordEncoder->isPasswordValid($user, $oldpass)) {
////              $form->get('oldpass')->addError(new FormError('password not incorrect'));
//                    return $this->render('user/ChangePassword.html.twig', ['form' => $form->createView()]);
//                }
//                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
//                $em->flush();
//                return $this->redirectToRoute('Update_data', ['id' => $user->getId()]);
//            }
//
//            return $this->render('user/ChangePassword.html.twig', [
//                'form' => $form->createView(),
//                'user' => $user
//            ]);
////        return $this->redirectToRoute('app_login');
//
//
//    }



}

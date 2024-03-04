<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    #[Route('/reponse/add/{id}', name: 'app_reponse_add')]
    public function add(Request $request, ManagerRegistry $managerRegistry,QuestionRepository $questionRepository,$id,MailerInterface $mailer): Response
    {
      if ($this->getUser()) {
          if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
              $qestionid = $questionRepository->find($id);
              $reponse = new Reponse();
              $currentDateTime = new \DateTime();
              $form = $this->createForm(ReponseType::class, $reponse);
              $reponse->setDatetempR($currentDateTime);
              $reponse->setQuestion($qestionid);
              $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
                  $description = $reponse->getDescriptionR();
                  $httpClient = HttpClient::create();
                  $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                      'query' => [
                          'content' => $description
                      ],

                      'headers' => [
                          'User-ID' => 'alaayari',
                          'API-Key' => 'EYgRcIUx1tmxyjutrrP8uywr2NQXXNF3Fyc9XVq7BUGGyiis',
                      ]
                  ]);
                  if ($response->getStatusCode() === 200) {
                      $result = $response->toArray();
                      if ($result['is-bad']) {
                          $this->addFlash('danger', '</i>Your comment contains inappropriate language and cannot be posted.');
                          return $this->redirectToRoute('reponse_bad_words', ['id' => $id]);
                      }
                  }

                  $this->sendEmail($description, $mailer);
                  $reponse->setMedecin($this->getUser());
                  $em = $managerRegistry->getManager();
                  $em->persist($reponse);
                  $em->flush();
                  return $this->redirectToRoute('app_question_show_id', ['id' => $qestionid->getId()]);
              }
              return $this->render('reponse/reponse_add.html.twig', ['f' => $form->createview()]);
          }

          }
        return $this->redirectToRoute('app_login');


    }

    #[Route('/reponse/edit/{id}', name: 'app_reponse_edit')]
    function edit(QuestionRepository $questionRepository,ReponseRepository $repository, $id, Request $request, ManagerRegistry $managerRegistry,MailerInterface $mailer): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $reponse = $repository->find($id);
                $question = $reponse->getQuestion()->getId();
                $currentDateTime = new \DateTime();
                $form = $this->createForm(ReponseType::class, $reponse);

                $reponse->setDatetempR($currentDateTime);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $description = $reponse->getDescriptionR();
                    $httpClient = HttpClient::create();
                    $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                        'query' => [
                            'content' => $description
                        ],

                        'headers' => [
                            'User-ID' => 'alaayari',
                            'API-Key' => 'EYgRcIUx1tmxyjutrrP8uywr2NQXXNF3Fyc9XVq7BUGGyiis',
                        ]
                    ]);
                    if ($response->getStatusCode() === 200) {
                        $result = $response->toArray();
                        if ($result['is-bad']) {
                            $this->addFlash('danger', '</i>Your comment contains inappropriate language and cannot be posted.');
                            return $this->redirectToRoute('reponse_bad_words', ['id' => $id]);
                        }
                    }
                    $this->modifierEmail($description, $mailer);

                    $em = $managerRegistry->getManager();
                    $em->flush();
                    return $this->redirectToRoute('app_question_show_id', ['id' => $question]);
                }
                return $this->render('reponse/reponse_edit.html.twig', ['f' => $form->createView()]);
            }
        }
        return $this->redirectToRoute('app_login');
    }



    #[Route('/reponse/delete/{id}', name: 'app_reponse_delete')]
    function deleter($id, ReponseRepository $repository, ManagerRegistry $managerRegistry) : \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
        $reponse = $repository->find($id);
        $question = $reponse->getQuestion()->getId();
        $em = $managerRegistry->getManager();
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('app_question_show_id', ['id' => $question]);
    }

       }
        return $this->redirectToRoute('app_login');

    }
    #[Route('/reponse_bad_words', name: 'reponse_bad_words')]

    function Affiche_bad_rep(ReponseRepository $repository)
    {if ($this->getUser()) {
        $Commentaire = $repository->findAll();
        return $this->render('reponse/bad_word.html.twig', ['description' => $Commentaire]);

    }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/pin/{id}', name: 'app_pin')]
    public function pinned(ReponseRepository $repository, $id, ManagerRegistry $managerRegistry)
    {
        if ($this->getUser()) {
            $reponse = $repository->find($id);
            $question = $reponse->getQuestion()->getId();
            $maxPinnedCount = 5;
            $pinnedComments = $repository->countPinnedCommentsForQuestion($question);
            if ($reponse->isPinned() == false && $pinnedComments < $maxPinnedCount) {
                $reponse->setPinned(true);
            } else {
                $reponse->setPinned(false);
            }
            $em = $managerRegistry->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_question_show_id', ['id' => $question]);
        }

        return $this->redirectToRoute('app_login');

    }
    private function sendEmail($description, MailerInterface $mailer): void
    {
        $emailText = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <title>My eDr</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 style='color: #007bff;'>Nouveau Commentaire a eté Creé</h2>
                <p><strong>Description:</strong> $description</p>
            </div>
        </body>
        </html>
    ";

        $email = (new Email())
            ->from(new Address('myedr@email.com','My eDr'))
            ->to('myedr83@gmail.com')
            ->subject('Un Nouveau Commentaire a été Deposer')
            ->html($emailText);
        $mailer->send($email);
    }
    private function modifierEmail($description, MailerInterface $mailer): void
    {
        $emailText = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <title>My eDr</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 style='color: #007bff;'>un Nouveau Commentaire modifier</h2>
                <p><strong>Description:</strong> $description</p>
            </div>
        </body>
        </html>
    ";

        $email = (new Email())
            ->from(new Address('myedr@email.com','My eDr'))
            ->to('myedr83@gmail.com')
            ->subject('Un Commentaire a été Modifier')
            ->html($emailText);
        $mailer->send($email);
    }

    #[Route('/bad_words', name: 'question_bad_words')]
    function Affiche_bad(QuestionRepository $repository)
    {
        if ($this->getUser()){
        $Question = $repository->findAll();
        return $this->render('question/bad_word.html.twig', ['description' => $Question]);
    }
return $this->redirectToRoute('app_login');
    }

}

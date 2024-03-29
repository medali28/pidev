<?php

namespace App\Controller;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class QuestionController extends AbstractController
{
    #[Route('/question/afficher/{id}', name: 'app_question_show_id')]
    function showby(QuestionRepository $repository ,$id, ReponseRepository $reponseRepository)
    {
        $question=$repository->find($id);
        $reponse=$reponseRepository->findByPinned($question);
        return $this->render('question/afficher_question.html.twig',['question'=>$question,'reponse'=>$reponse]);
    }

    #[Route('/question/show', name: 'app_question_show')]
    function show(QuestionRepository $repository )
    {
        $questions = $repository->findBy([], ['datetempQ' => 'DESC']);
        return $this->render('question/question_show.html.twig',['questions'=>$questions]);
    }
    #[Route('/question/question_add', name: 'app_question_add')]
    public function add(Request $request,ManagerRegistry $managerRegistry,sluggerinterface $slugger,mailerinterface $mailer): Response
    {
        if ($this->getUser() ) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
                $currentDateTime = new \DateTime();
                $question = new Question();
                $form = $this->createForm(QuestionType::class, $question);
                $question->setDatetempQ($currentDateTime);
                $question->setPatient($this->getUser());
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $description = $question->getDescription();
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
                            $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                            return $this->redirectToRoute('question_bad_words');
                        }
                    }


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
                    $title = $question->getTitle();
                    $photo = $question->getImage();
                    $this->sendEmail($photo, $title, $description, $mailer);
                    $em = $managerRegistry->getManager();
                    $em->persist($question);
                    $em->flush();
                    return $this->redirectToRoute('app_question_show');
                }
                return $this->render('question/question_add.html.twig', ['f' => $form->createview()]);
            }
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/question/edit/{id}', name: 'app_question_edit')]
    function edit(QuestionRepository $repository,$id,Request $request ,ManagerRegistry $managerRegistry,sluggerinterface $slugger,MailerInterface $mailer)
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
                $question = $repository->find($id);
                $currentDateTime = new \DateTime();
                $form = $this->createForm(QuestionType::class, $question);
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

                        $description = $question->getDescription();
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
                                $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                                return $this->redirectToRoute('question_bad_words', ['id' => $id]);
                            }
                        }
                        $title = $question->getTitle();
                        $photo = $question->getImage();
                        $this->modifierEmail($photo, $title, $description, $mailer);
                        $em = $managerRegistry->getManager();
                        $em->flush();
                        return $this->redirectToRoute('app_question_show');
                    }
                }

                return $this->render('question/edit.html.twig', ['f' => $form->createView()]);
            }
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/question/delete/{id}', name: 'app_question_delete')]
    function delete($id,QuestionRepository $repository,ManagerRegistry $managerRegistry)
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_PATIENT") {
                $question = $repository->find($id);
                $em = $managerRegistry->getManager();
                $em->remove($question);
                $em->flush();
                return $this->redirectToRoute('app_question_show');
            }
        }
        return $this->redirectToRoute('app_login');
    }

    private function sendEmail($photo,  $title,$description, MailerInterface $mailer): void
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
                <h2 style='color: #007bff;'>New Question Created</h2>
                <p><strong>Title:</strong> $title</p>
                <p><strong>Description:</strong> $description</p>
                <p><strong>image:</strong> <img src='/image/$photo' alt='Question Photo'></p>
            </div>
        </body>
        </html>
    ";

        $email = (new Email())
            ->from(new Address('myedr@email.com','My eDr'))
            ->to('myedr83@gmail.com')
            ->subject('Un Nouveau Question a été créé')
            ->html($emailText);
        $mailer->send($email);
    }
    private function modifierEmail($photo,  $title,$description, MailerInterface $mailer): void
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
                <h2 style='color: #007bff;'>New Question Created</h2>
                <p><strong>Title:</strong> $title</p>
                <p><strong>Description:</strong> $description</p>
                <p><strong>image:</strong> <img src='/image/$photo' alt='Question Photo'></p>
            </div>
        </body>
        </html>
    ";

        $email = (new Email())
            ->from(new Address('myedr@email.com','My eDr'))
            ->to('myedr83@gmail.com')
            ->subject('Un Question a été Modifier')
            ->html($emailText);
        $mailer->send($email);
    }

    #[Route('/bad_words', name: 'question_bad_words')]
    function Affiche_bad(QuestionRepository $repository)
    {
        if ($this->getUser()) {
            $Question = $repository->findAll();
            return $this->render('question/bad_word.html.twig', ['description' => $Question]);
        }
        return $this->redirectToRoute('app_login');
    }
}

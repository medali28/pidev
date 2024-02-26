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
        $reponse=$reponseRepository->findByQuestion($question);
        return $this->render('question/afficher_question.html.twig',['question'=>$question,'reponse'=>$reponse]);
    }

    #[Route('/question/show', name: 'app_question_show')]
    function show(QuestionRepository $repository )
    {
        $question=$repository->findAll();
        return $this->render('question/question_show.html.twig',['question'=>$question]);
    }
    #[Route('/question/question_add', name: 'app_question_add')]
    public function add(Request $request,ManagerRegistry $managerRegistry,sluggerinterface $slugger/*,mailerinterface $mailer*/): Response
    {

        $currentDateTime = new \DateTime();
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $question->setDatetempQ($currentDateTime);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
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

            if ($result['is-bad'] ) {
                $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                return $this->redirectToRoute('bad_words');
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
            $questionmailed = $form->getData();
            //     $this->sendEmail($questionmailed, $mailer);
            $em = $managerRegistry->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('app_question_show');
        }
        return $this->render('question/question_add.html.twig', ['f' => $form->createview()]);

    }
    #[Route('/question/edit/{id}', name: 'app_question_edit')]
    function edit(QuestionRepository $repository,$id,Request $request ,ManagerRegistry $managerRegistry,sluggerinterface $slugger)
    {
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

                    if ($result['is-bad'] ) {
                        $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                        return $this->redirectToRoute('bad_words');
                    }
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
                        return $this->redirectToRoute('bad_words', ['id' => $id]);
                    }
                }
                $em = $managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute('app_question_show');
            }
        }

            return $this->render('question/edit.html.twig', ['f' => $form->createView()]);
        }

    #[Route('/question/delete/{id}', name: 'app_question_delete')]
    function delete($id,QuestionRepository $repository,ManagerRegistry $managerRegistry)
    {
        $question=$repository->find($id);
        $em=$managerRegistry->getManager();
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('app_question_show');
    }

    private function sendEmail($questionmailed,MailerInterface $mailer)
    {


        $email = (new Email())

            ->from(new Address('myedr@gmail.com', 'My eDr'))
            ->to('alaayari832@gmail.com')
            ->subject('Un Nouveau Question a eté creé')
            ->text($questionmailed);

        $mailer->send($email);

        return $this->redirectToRoute('app_question_show');
    }

    #[Route('/bad_words', name: 'question_bad_words')]
    function Affiche_bad(QuestionRepository $repository)
    {
        $Question = $repository->findAll();
        return $this->render('question/bad_word.html.twig', ['description' => $Question]);
    }
}

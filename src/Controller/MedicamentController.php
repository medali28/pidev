<?php

namespace App\Controller;

use App\Entity\ForbiddenKeyword;
use App\Entity\Medicament;
use App\Form\AjoutmedType;
use App\Form\ModifiermedType;

use App\Repository\MedicamentRepository;
use Doctrine\Persistence\ManagerRegistry;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use thiagoalessio\TesseractOCR\TesseractOCR;

class MedicamentController extends AbstractController
{



    #[Route('/gestion/mecimaent', name: 'app_gestion_donation')]//show all medicaments
    public function showallDonation(Request $request, MedicamentRepository $medicamentRepository, PaginatorInterface $paginator): Response
    {
        $query = $medicamentRepository->findAll();
        $medicaments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );


        return $this->render('medicament/showall_medicaments.html.twig', [
            'medicaments' => $medicaments,
        ]);
    }
    #[Route('/user-medicaments', name: 'user_medicaments')]//medicamnt of the logged in user
    public function showUserMedicaments(MedicamentRepository $medicamentRepository): Response
    {
        $userid = $this->getUser();


        return $this->render('medicament/medicalentsperuser.html.twig', array(
            'medicaments'=>$medicamentRepository->findMedicamentsBuser($userid)))
            ;
    }
    #[Route('/ajout/donation', name: 'ajoutdonation')]

    public function ajoutProduit(Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger)
    {

        $medicament = new Medicament();

        $form = $this->createForm(AjoutmedType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                // Generate a unique filename for the image
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the uploaded file to the target directory
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }

                // Use TesseractOCR to extract text from the image
                $imagePath = $this->getParameter('images_directory') . '/' . $newFilename;
                $text = (new TesseractOCR($imagePath))->run();

                // Retrieve forbidden keywords from the database
                $forbiddenKeywords = $managerRegistry->getRepository(ForbiddenKeyword::class)->findAll();

                // Perform text analysis by checking against each forbidden keyword
                foreach ($forbiddenKeywords as $keyword) {
                    if (stripos($text, $keyword->getKeyword()) !== false) {
                        // Handle the case when forbidden keyword is found
                        // You can delete the uploaded image here if needed
                        unlink($imagePath);
                        $this->addFlash('error', 'Forbidden keyword detected in the image.');
                        return $this->redirectToRoute('app_gestion_donation');
                    }
                }

                // Set the image filename in the entity
                $medicament->setImage($newFilename);
            }

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($medicament);
            $entityManager->flush();

            $this->addFlash('success', 'Medicament ajouté avec succes!');

            return $this->redirectToRoute('app_gestion_donation');
        }

        return $this->render('medicament/addprod.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateSafeFilename($originalFilename):string
    {
        // Remove any non-alphanumeric characters from the filename
        $filename = preg_replace('/[^a-zA-Z0-9]/', '-', $originalFilename);

        // Remove any consecutive dashes
        $filename = preg_replace('/-{2,}/', '-', $filename);

        // Remove any leading or trailing dashes
        $filename = trim($filename, '-');

        // Add a unique ID to the filename to ensure it's unique
        $newFilename = $filename.'-'.uniqid();

        return $newFilename;
    }

    #[Route('/update/{id}', name: 'updateProduit')]
    public function  updateProduit(MedicamentRepository $medicamentRepository,$id,  Request  $request,ManagerRegistry $managerRegistry) : Response
    {
        $medciament=$medicamentRepository->find($id);
        $form = $this->createForm(ModifiermedType::class, $medciament);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$managerRegistry->getManager();
            $em->flush();

            return $this->redirectToRoute('app_gestion_donation');
        }
        return $this->renderForm("medicament/update.html.twig",
            ["form"=>$form]) ;


    }
    #[Route("/delete/{id}", name:'deleteProduit')]
    public function delete($id, MedicamentRepository $medicamentRepository,ManagerRegistry $managerRegistry)
    {
        $medciament=$medicamentRepository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($medciament);
        $em->flush() ;
        $remainingMedicaments = $medicamentRepository->findAll();
        foreach ($remainingMedicaments as $index => $remainingMedicament) {
            $remainingMedicament->setId($index + 1);
            $em->persist($remainingMedicament);
        }
        $em->flush();

        return $this->redirectToRoute('app_gestion_donation');
    }


}

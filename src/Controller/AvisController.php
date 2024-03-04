<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reclamation;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('{id}/avis/{idm}', name: 'app_avis')]
    public function avis(Request $request,$idm, UserRepository $userRepository ,ManagerRegistry $managerRegistry, AvisRepository $repository, ReclamationRepository $reclamationRepository, int $id): Response
    {
        if ($this->getUser()) {
            $avis = new Avis();
            $form = $this->createForm(AvisType::class, $avis);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $avis->setPatient($this->getUser());
                $avis->setMedecin($userRepository->find($idm));
                if ($avis->getRate() <= 2) {
                    $reclamation = new Reclamation();
                    $reclamation->setPatient($this->getUser());
                    $reclamation->setMedecin($userRepository->find($idm));
                    $reclamation->setEtat("Sous examination");
                    $reclamation->setDescriptionRec($avis->getDescription());
                    $reclamation->setSujet($avis->getSujet());
                    $reclamation->setDateRec(new \DateTimeImmutable());
                    $reclamation->setType("Avis");
                    $avis->setReclamation($reclamation);
                    $em = $managerRegistry->getManager();
                    $em->persist($reclamation);
                    $em->flush();
                    $em = $managerRegistry->getManager();
                    $em->persist($avis);
                    $em->flush();
                } else {
                    $em = $managerRegistry->getManager();
                    $em->persist($avis);
                    $em->flush();
                }

//


                return $this->redirectToRoute('mes_avis', ['id' => $id]);
            }

            return $this->render('avis/avis.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/edit-avis/{ida}', name: 'edit_avis')]
    public function editavis(Request $request, ManagerRegistry $managerRegistry, int $ida, int $id, AvisRepository $avisRepository): Response
    {
        if ($this->getUser()) {
            $avis = $avisRepository->find($ida);

            $form = $this->createForm(AvisType::class, $avis);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $managerRegistry->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('mes_avis', ['id' => $id]);
            }

            return $this->render('avis/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('{id}/mes-avis', name: 'mes_avis')]
    public function mesavis(AvisRepository $repository, int $id): Response
    {
        if ($this->getUser()) {
            $avis = $repository->findBy(['patient' => $id]);

            return $this->render('avis/show.html.twig', [
                'avis' => $avis,
                'id' => $id,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{id}/details_avis/{medid}', name: 'details_avis')]
    public function deleteavis(int $id, int $medid, AvisRepository $repository, ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()) {
            $avis = $repository->findBy(['medecin' => $medid]);
            $totalRate = 0;
            $numberOfReviews = count($avis);
            $countOf1 = 0;
            $countOf2 = 0;
            $countOf3 = 0;
            $countOf4 = 0;
            $countOf5 = 0;
            foreach ($avis as $avis) {
                $totalRate += $avis->getRate();
                $rating = $avis->getRate();
                switch ($rating) {
                    case 1:
                        $countOf1++;
                        break;
                    case 2:
                        $countOf2++;
                        break;
                    case 3:
                        $countOf3++;
                        break;
                    case 4:
                        $countOf4++;
                        break;
                    case 5:
                        $countOf5++;
                        break;
                    default:
                        break;
                }

            }

            $averageRate = $numberOfReviews > 0 ? $totalRate / $numberOfReviews : 0;

            return $this->render('avis/details.html.twig', [
                'avis' => $avis,
                'moyenne' => $averageRate,
                'total_reviews' => $numberOfReviews,
                'c1' => $countOf1, 'c2' => $countOf2, 'c3' => $countOf3, 'c4' => $countOf4, 'c5' => $countOf5,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/{id}/avis/search', name: 'search_avis')]
    public function searchavis(Request $request, AvisRepository $avisRepository, int $id): Response
    {
        if ($this->getUser()) {
            $term = $request->query->get('term');

            if (!$term) {
                return $this->redirectToRoute('mes_avis', ['id' => $id]);
            }

            $avis = $avisRepository->findByPatientAndSearchTerm($id, $term);

            return $this->render('avis/show.html.twig', [
                'avis' => $avis,
                'id' => $id
            ]);

        }return $this->redirectToRoute('app_login');
    }
}

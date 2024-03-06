<?php

namespace App\Controller;

use App\Entity\ProgressBar;
use App\Form\EditProgreesType;
use App\Form\TargetFormType;
use App\Repository\ProgressBarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\Translation\t;

class ProgressController extends AbstractController
{
    #[Route('/progress/create', name: 'progress_create')]
    public function createmedecin(Request $request,ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $progressBar = new ProgressBar();
                $progressBar->setCurrent(0);
                $form = $this->createForm(TargetFormType::class, $progressBar);
                $form->handleRequest($request);
                $progressBar->setUser($this->getUser());;
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $managerRegistry->getManager();
                    $entityManager->persist($progressBar);
                    $entityManager->flush();

                    return $this->redirectToRoute('progress_view', ['id' => $progressBar->getId()]);
                }

                return $this->render('progress/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }
     #[Route('/progress/{id}/edit', name: 'progress_edit_med')]
    public function editmed(Request $request,$id ,ProgressBarRepository $progressBarRepository ,ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN") {
                $progressBar = $progressBarRepository->find($id);
                $form = $this->createForm(TargetFormType::class, $progressBar);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $managerRegistry->getManager();
                    $entityManager->flush();

                    return $this->redirectToRoute('progress_view', ['id' => $progressBar->getId()]);
                }
                return $this->render('progress/edit_med.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('app_login');
    }




    #[Route('/progress', name: 'progress_view')]
    public function view(ProgressBarRepository $progressBarRepository,PaginatorInterface $paginator,Request $request): Response
    {
        if ($this->getUser() ) {
                $query = $progressBarRepository->findAll();
                $progressBar = $paginator->paginate(
                    $query,
                    $request->query->getInt('page', 1), /*page number*/
                    4 /*limit per page*/
                );
                return $this->render('progress/view.html.twig', [
                    'progressBar' => $progressBar,
                ]);
            }




        return $this->redirectToRoute('app_login');
    }
    #[Route('/progress/{id}', name: 'progress_edit')]
    public function Pay($id, Request $request, ManagerRegistry $managerRegistry, ProgressBarRepository $progressBarRepository): Response
    {
        if ($this->getUser()) {
            $progressBar = $progressBarRepository->find($id);

            // Create form for editing current value
            $form = $this->createForm(EditProgreesType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $amounttopay = $form->get('flous')->getData();
                $targetValue = $progressBar->getTarget();

                if ($amounttopay <= $targetValue) {
                    $progressBar->setCurrent($progressBar->getCurrent() + $amounttopay);
                    $entityManager = $managerRegistry->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute('stripe_payment', ['amount' => $amounttopay]);
                } else {
                    $this->addFlash('error', 'Current value cannot exceed the target value.');
                }
            }

            return $this->render('progress/edit.html.twig', [
                'form' => $form->createView(),
                'progressBar' => $progressBar,
            ]);

        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/stripe/payment', name: 'stripe_payment')]

    public function stripePayment(Request $request)
    {
        if ($this->getUser()) {
            // Get amount from request
            $amount = $request->query->get('amount');

            // Create payment session with Stripe
            Stripe::setApiKey('sk_test_51Oo6gMEbvcankNU3vagYs8vZKYbEoKrIqEtFVRT5hZYX2V6SYw9IZlpNj7uw3YbspPp8jR9gesDnXFwi7KUexaAJ00kBgKyXTx');
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $amount * 100, // Amount in cents
                            'product_data' => [
                                'name' => 'Progress Bar Payment',
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('stripe_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('stripe_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->redirect($session->url);
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route('/stripe/payment/success', name: 'stripe_payment_success')]
    public function stripePaymentSuccess(Request $request, ManagerRegistry $managerRegistry, ProgressBarRepository $progressBarRepository)
    {
        // Handle payment success
        // Update progress bar based on the payment amount
if ($this->getUser()){

        return $this->redirectToRoute('progress_view');
    }
return $this->redirectToRoute('app_login');
    }

    #[Route('/stripe/payment/cancel', name: 'stripe_payment_cancel')]
    public function stripePaymentCancel()
    {
        if ($this->getUser()){

            // Handle payment cancellation
        $this->addFlash('error', 'Payment was cancelled.');
        return $this->redirectToRoute('progress_view');
    }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/progress/{id}/delete', name: 'progress_delete')]
    public function delete($id, ProgressBarRepository $progressBarRepository, ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()){
            if ($this->getUser()->getRoles()[0] == "ROLE_MEDECIN"){
        $progressBar = $progressBarRepository->find($id);

        if (!$progressBar) {
            throw $this->createNotFoundException('Progress bar not found');
        }

        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($progressBar);
        $entityManager->flush();

        $this->addFlash('success', 'Progress bar deleted successfully.');

        return $this->redirectToRoute('progress_view');
    }}
return $this->redirectToRoute('app_login');
}




}






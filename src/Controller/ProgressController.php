<?php

namespace App\Controller;

use App\Entity\ProgressBar;
use App\Form\EditProgreesType;
use App\Form\TargetFormType;
use App\Repository\ProgressBarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProgressController extends AbstractController
{
    #[Route('/progress/create', name: 'progress_create')]
    public function create(Request $request,ManagerRegistry $managerRegistry): Response
    {
        $progressBar = new ProgressBar();
        $form = $this->createForm(TargetFormType::class, $progressBar);
        $form->handleRequest($request);

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



    #[Route('/progress', name: 'progress_view')]

    public function view(ProgressBarRepository $progressBarRepository): Response
    {
        $progressBar=$progressBarRepository->findAll();
        return $this->render('progress/view.html.twig', [
            'progressBar' => $progressBar,
        ]);
    }
    #[Route('/progress/{id}', name: 'progress_edit')]

    public function edit($id, Request $request, ManagerRegistry $managerRegistry, ProgressBarRepository $progressBarRepository): Response
    {
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
    #[Route('/stripe/payment', name: 'stripe_payment')]

    public function stripePayment(Request $request)
    {
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


    #[Route('/stripe/payment/success', name: 'stripe_payment_success')]
    public function stripePaymentSuccess(Request $request, ManagerRegistry $managerRegistry, ProgressBarRepository $progressBarRepository)
    {
        // Handle payment success
        // Update progress bar based on the payment amount


        return $this->redirectToRoute('progress_view');
    }

    #[Route('/stripe/payment/cancel', name: 'stripe_payment_cancel')]


    public function stripePaymentCancel()
    {
        // Handle payment cancellation
        $this->addFlash('error', 'Payment was cancelled.');
        return $this->redirectToRoute('progress_view');
    }


}






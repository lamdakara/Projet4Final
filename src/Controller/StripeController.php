<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        //Tableau stripe
        $care_for_stripe = [];

        //Nom du site
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        //Récupération dans la BDD
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            $this->redirectToRoute('order');
        }

        //Boucle récupérer les valeurs de OrderDetails 
        foreach ($order->getOrderDetails()->getValues() as $care) {
            $care_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $care->getPrice(),
                    'product_data' => [
                        'name' => $care->getProduct(),
                        'images' => [$YOUR_DOMAIN],
                    ],
                ],
                'quantity' => $care->getQuantity(),
            ];
        }

        //Api Stripes
        Stripe::setApiKey('sk_test_51LiI1uJ85NHrhmvQeFXnIELYoa59KtVUXvguUUFgZEloyP8v2Hda0OxAReatEcTYlmhBFSMrJ3PUmViGxgDKZygS00kBKAuB7h');

        //Création des routes après paiements
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $care_for_stripe
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->url);

        return $this->redirect($checkout_session->url);
    }
}

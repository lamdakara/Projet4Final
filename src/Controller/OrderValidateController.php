<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{

    private $entityManager;

    //Récupération dans la BDD
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate'), IsGranted('ROLE_USER')]
    public function index(Cart $cart, $stripeSessionId) : Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        //Voir si c'est bien l'utilisateur connecté
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getIsPaid() == 0) {
            // Vider la session "cart"
            $cart->remove();

            // Modifier le statut isPaid de notre commande en mettant 1
            $order->setIsPaid(1);
            $this->entityManager->flush();

            // Envoyer un email à notre client pour lui confirmer sa commande
            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstname() . "<br/>Merci pour votre réservation.<br><br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam expedita fugiat ipsa magnam mollitia optio voluptas! Alias, aliquid dicta ducimus exercitationem facilis, incidunt magni, minus natus nihil odio quos sunt?";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre réservation sur Lili Giroud est bien validée.', $content);
        }

        //Gérer la vue
        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}

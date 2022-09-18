<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Repository\BookingRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\MailjetService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class OrderValidateController extends AbstractController
{

    private $entityManager;

    //Récupération dans la BDD
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate'), IsGranted('ROLE_USER')]
    public function index(Request $request, $stripeSessionId, MailjetService $mailjetService, BookingRepository $bookingRepository, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator) : Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'stripeSessionId' => $stripeSessionId,
            'isPaid' => false
        ]);

        if (!$order) {
            $this->addFlash('danger', 'Commande non trouvé');

            return $this->redirectToRoute('home');
        }

        $user = $order->getUser();

        // une fois que l'utilisateur et rediriger depuis stripe il perd la session 
        // du coup on est obligé de forcé ça connexion
        $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );

        // On récupère tous les réservations pas encore payer par l'utilisateur connecté
        $bookings = $bookingRepository->findBy(['payer' => false, 'user' => $this->getUser()]);

        // On set le payement à true
        foreach($bookings as $booking) {
            $booking->setPayer(true);
        }

        // Modifier le statut isPaid de notre commande en mettant 1
        $order->setIsPaid(true);
        $this->entityManager->flush();

        // Envoyer un email à notre client pour lui confirmer sa commande
        $content = "Bonjour " . $order->getUser()->getFirstname() . "<br/>Merci pour votre réservation.<br><br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam expedita fugiat ipsa magnam mollitia optio voluptas! Alias, aliquid dicta ducimus exercitationem facilis, incidunt magni, minus natus nihil odio quos sunt?";
        $mailjetService->sendEmail($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre réservation sur Lili Giroud est bien validée.', $content);

        $this->addFlash('success', 'Votre paiement à bien été réceptionné, Merci !');

        //Gérer la vue
        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}

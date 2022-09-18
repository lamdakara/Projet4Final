<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Booking;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    //Récupération de la BDD
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Crée notre commande à partir de panier
    #[Route('/commande', name: 'order')]
    public function index(): Response
    {

        // Si l'utilisateur n'a pas de valeur dans adresse le renvoyer à ajouter une adresse
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        //Création formulaire avec OrderType
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $carts = $this->entityManager->getRepository(Booking::class)->findBy(["user" => $this->getUser()], []);

        // Gérer la vue
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'carts' => $carts
        ]);
    }

    //Crée notre commande dans la base de donnée
    #[Route('/commande/recapitulatif', name: 'order_summary', methods: ('POST'))]
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        //Vérifie si la commande est existante
        if ($form->isSubmitted() && $form->isValid()) {
            //Initialiser une date
            $date = new \DateTimeImmutable();

            //Initialiser une livraison
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname() . '' . $delivery->getLastname();
            $delivery_content .= '<br/>' . $delivery->getPhone();
            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . '' . $delivery->getCity();

            // Enregistrer ma commande Order()
            $order = new Order();
            $reference = $date->format('dmY') . '-' . uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setDelivery(($delivery_content));
            $order->setisPaid(0);

            $this->entityManager->persist($order);

            //Enregistrer mes produits OrderDetails()
            $carts = $this->entityManager->getRepository(Booking::class)->findBy(["user" => $this->getUser()], []);

            foreach ($carts as $care) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($care->getService()->getTitre());
                $orderDetails->setQuantity(1);
                $orderDetails->setPrice($care->getService()->getPrix());
                $orderDetails->setTotal($care->getService()->getPrix());

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            // Gérer la vue
            return $this->render('order/add.html.twig', [
                'form' => $form->createView(),
                'cart' => $carts,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}

<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    //Récupération de la BDD
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Gérer la vue
    #[Route('/compte', name: 'account'), IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mescommandes/{reference}', name: 'account_order_show'), IsGranted('ROLE_USER')]
    public function show($reference)
    {
        //Récupération dans la BDD de order et de référence
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        //Redirection vers compte
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account');
        }

        //Gérer la vue
        return $this->render('account/ordershow.html.twig', [
            'order' => $order
        ]);
    }

}

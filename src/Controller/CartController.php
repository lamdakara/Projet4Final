<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Booking;
use App\Entity\Care;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    // Récupération de la BDD
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Gérer la vue panier
    #[Route('/panier', name: 'cart'), IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManager)
    {
        $carts = $this->entityManager->getRepository(Booking::class)->findBy(["user" => $this->getUser(), "payer" => false]);

        return $this->render('cart/index.html.twig', [
            'carts' => $carts
        ]);
    }

    // Ajouter un élément au panier
    #[Route('/cart/add/{id}', name: 'add_to_cart'), IsGranted('ROLE_USER')]
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    // Supprimer un élement du panier
    #[Route('/cart/delete/{id}', name: 'delete_to_cart'), IsGranted('ROLE_USER')]
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

}

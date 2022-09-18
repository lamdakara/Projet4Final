<?php

namespace App\Classe;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    //Session interface avec symfony 6
    private $stack;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $stack)
    {
        return $this->stack = $stack;
        $this->entityManager = $entityManager;
    }

    // Ajouter un élément au panier
    public function add($id)
    {
        $session = $this->stack->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
    }

    public function get()
    {
        $methodget = $this->stack->getSession();
        return $methodget->get('cart');
    }

    // Supprimer un élément du panier
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }
}

<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{

    // Récupération de la BDD
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Gérer la vue
    #[Route('/compte/adresses', name: 'account_address'), IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouteruneadresse', name: 'account_address_add'), IsGranted('ROLE_USER')]
    public function add(Cart $cart, Request $request): Response
    {
        // Ecoute et envoi le formulaire à la vue twig
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        //Traiter notre formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Lier l'utilisateur à l'adresse
            $address->setUser($this->getUser());

            //Enregistrer les données
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            //Si j'ai des produits dans le panier me renvoie à mon tunnel d'achat
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }

            //Renvoyer à la fin l'utilisateur à toute ses adresses
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/modifieruneadresse/{id}', name: 'account_address_edit'), IsGranted('ROLE_USER')]
    public function edit(Request $request, $id): Response
    {
        // Récupère l'id
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        //Vérifie si l'adresse existe 
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        //Traiter notre formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            //Renvoyer à la fin l'utilisateur à toute ses adresses
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimeruneadresse/{id}', name: 'account_address_delete'), IsGranted('ROLE_USER')]
    public function delete($id): Response
    {
        // Récupère l'id
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        //Vérifie si l'adresse existe 
        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        //Renvoyer à la fin l'utilisateur à toute ses adresses
        return $this->redirectToRoute('account_address');
    }
}

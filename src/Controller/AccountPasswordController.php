<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    // Récupération dans la BDD
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifiermonmotdepasse', name: 'account_password'), IsGranted('ROLE_USER')]

    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        // Sauvegarder les informations dans la BDD
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Encodage du mot de passe
            $old_pwd = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $passwordCrypte = $encoder->hashPassword($user, $new_pwd);
                $user->setPassword($passwordCrypte);

                $entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        // Gérer la vue
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}

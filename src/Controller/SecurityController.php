<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Connexion
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //Si utilisateur envoyé vers compte
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Déconnexion
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }
}
